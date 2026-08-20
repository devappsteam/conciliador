<template>
	<tr v-for="i in 5" :key="i" class="animate-pulse">
		<td class="px-6 py-4">
			<div class="flex flex-col gap-1">
				<div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-sm w-48"></div>
				<div class="h-3 bg-gray-100 dark:bg-gray-800 rounded-sm w-32"></div>
			</div>
		</td>
		<td class="px-6 py-4">
			<div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-sm w-28"></div>
		</td>
		<td class="px-6 py-4">
			<div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-16"></div>
		</td>
		<td class="px-6 py-4 text-right">
			<div class="h-8 bg-gray-200 dark:bg-gray-700 rounded-lg w-20 ml-auto"></div>
		</td>
	</tr>
</template>
