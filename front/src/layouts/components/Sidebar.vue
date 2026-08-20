<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import {
	Building2,
	ChevronDown,
	CreditCard,
	Landmark,
	LayoutDashboard,
	PanelLeftClose,
	PanelLeftOpen,
	Users2,
	type LucideIcon,
} from '@lucide/vue'
import { useAuthStore } from '@/modules/auth/stores/useAuthStore'

defineProps<{ isOpen: boolean }>()
defineEmits<{ (e: 'close'): void }>()

const authStore = useAuthStore()
const route = useRoute()

interface MenuItem {
	label: string
	routeName: string
	icon: LucideIcon
	permission?: string
}

interface MenuGroup {
	key: string
	name: string
	items: MenuItem[]
}

// Estrutura pensada para crescer: novos módulos entram como novos grupos/itens sem alterar o layout
const menuStructure: MenuGroup[] = [
	{
		key: 'general',
		name: 'Geral',
		items: [{ label: 'Dashboard', routeName: 'dashboard', icon: LayoutDashboard }],
	},
	{
		key: 'clients',
		name: 'Clientes',
		items: [{ label: 'Empresas', routeName: 'companies', icon: Building2 }],
	},
	{
		key: 'registrations',
		name: 'Cadastros',
		items: [
			{ label: 'Adquirentes', routeName: 'integrations.acquirers', icon: CreditCard },
			{ label: 'Bancos', routeName: 'integrations.banks', icon: Landmark },
			{ label: 'Bandeiras', routeName: 'integrations.brands', icon: CreditCard },
		],
	},
	{
		key: 'settings',
		name: 'Configurações',
		items: [{ label: 'Usuários', routeName: 'settings.users', icon: Users2 }],
	},
]

const visibleMenu = computed(() =>
	menuStructure
		.map((group) => ({
			...group,
			items: group.items.filter((item) => !item.permission || authStore.hasPermission(item.permission)),
		}))
		.filter((group) => group.items.length > 0),
)

const COLLAPSE_STORAGE_KEY = 'sidebar:collapsed'
const collapsed = ref(localStorage.getItem(COLLAPSE_STORAGE_KEY) === '1')

const toggleCollapsed = () => {
	collapsed.value = !collapsed.value
	localStorage.setItem(COLLAPSE_STORAGE_KEY, collapsed.value ? '1' : '0')
}

const isGroupActive = (group: MenuGroup) => group.items.some((item) => item.routeName === route.name)

const expandedGroups = ref<Set<string>>(new Set(menuStructure.map((group) => group.key)))

const toggleGroup = (key: string) => {
	if (collapsed.value) {
		return
	}

	if (expandedGroups.value.has(key)) {
		expandedGroups.value.delete(key)
	} else {
		expandedGroups.value.add(key)
	}
	// Força reatividade: Set mutado in-place não dispara o watcher de refs
	expandedGroups.value = new Set(expandedGroups.value)
}

const isGroupExpanded = (key: string) => collapsed.value || expandedGroups.value.has(key)

watch(
	() => route.name,
	() => {
		const activeGroup = menuStructure.find(isGroupActive)
		if (activeGroup) {
			expandedGroups.value.add(activeGroup.key)
			expandedGroups.value = new Set(expandedGroups.value)
		}
	},
	{ immediate: true },
)
</script>

<template>
	<div
		v-if="isOpen"
		class="fixed inset-0 z-50 bg-black/40 md:hidden transition-opacity"
		@click="$emit('close')"
	></div>

	<aside
		class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-gray-900 text-gray-300 border-r border-gray-800 transition-all duration-200 md:static md:translate-x-0"
		:class="[isOpen ? 'translate-x-0' : '-translate-x-full', collapsed ? 'md:w-19' : 'md:w-64']"
	>
		<div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-800 px-4">
			<img
				src="https://devapps.com.br/assets/images/logo.png"
				alt="Logo - DevApps"
				class="h-7 w-auto transition-all"
				:class="collapsed ? 'md:hidden' : ''"
			/>
			<span
				v-if="collapsed"
				class="hidden h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm font-bold text-white md:flex"
			>
				D
			</span>

			<button class="text-gray-400 hover:text-white md:hidden" @click="$emit('close')">
				<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
				</svg>
			</button>
		</div>

		<nav class="flex-1 space-y-4 overflow-y-auto overflow-x-hidden px-3 py-4">
			<div v-for="group in visibleMenu" :key="group.key">
				<button
					v-if="!collapsed"
					type="button"
					class="mb-1 flex w-full items-center justify-between px-2 py-1 text-[11px] font-semibold uppercase tracking-wider text-gray-500 hover:text-gray-300"
					@click="toggleGroup(group.key)"
				>
					<span>{{ group.name }}</span>
					<ChevronDown
						class="h-3.5 w-3.5 transition-transform"
						:class="isGroupExpanded(group.key) ? 'rotate-0' : '-rotate-90'"
					/>
				</button>

				<div v-show="isGroupExpanded(group.key)" class="space-y-0.5">
					<template v-for="item in group.items" :key="item.routeName">
						<router-link :to="{ name: item.routeName }" custom v-slot="{ href, navigate, isActive }">
							<a
								:href="href"
								:title="collapsed ? item.label : undefined"
								@click="navigate"
								class="group relative flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-[13px] font-medium transition-colors"
								:class="[
									isActive ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white',
									collapsed ? 'md:justify-center' : '',
								]"
							>
								<component :is="item.icon" class="h-4.5 w-4.5 shrink-0" />
								<span :class="collapsed ? 'md:hidden' : ''">{{ item.label }}</span>
							</a>
						</router-link>
					</template>
				</div>
			</div>
		</nav>

		<div class="shrink-0 border-t border-gray-800 p-3">
			<button
				class="hidden w-full items-center justify-center gap-2 rounded-lg py-2 text-xs font-medium text-gray-400 hover:bg-gray-800 hover:text-white md:flex"
				@click="toggleCollapsed"
			>
				<component :is="collapsed ? PanelLeftOpen : PanelLeftClose" class="h-4 w-4" />
				<span v-if="!collapsed">Recolher menu</span>
			</button>
		</div>
	</aside>
</template>
