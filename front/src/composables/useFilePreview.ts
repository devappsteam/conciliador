import { onUnmounted, ref } from 'vue'

export const useFilePreview = (initialPreviewUrl = '') => {
	const previewUrl = ref(initialPreviewUrl)
	const selectedFile = ref<File | null>(null)
	let objectUrl: string | null = null

	const revokeObjectUrl = () => {
		if (!objectUrl) {
			return
		}

		URL.revokeObjectURL(objectUrl)
		objectUrl = null
	}

	const setFile = (file: File | null, fallbackPreviewUrl = '') => {
		revokeObjectUrl()
		selectedFile.value = file

		if (!file) {
			previewUrl.value = fallbackPreviewUrl
			return
		}

		objectUrl = URL.createObjectURL(file)
		previewUrl.value = objectUrl
	}

	const reset = (fallbackPreviewUrl = '') => {
		setFile(null, fallbackPreviewUrl)
	}

	onUnmounted(() => {
		revokeObjectUrl()
	})

	return {
		previewUrl,
		selectedFile,
		setFile,
		reset,
		revokeObjectUrl,
	}
}
