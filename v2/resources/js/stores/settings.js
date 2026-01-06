import { defineStore } from 'pinia'

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        tableFontSize: 'medium' // 'small', 'medium', 'large'
    }),

    actions: {
        setTableFontSize(size) {
            this.tableFontSize = size
            // Apply to document root
            const rootFontSizes = {
                small: '0.75rem',    // 12px
                medium: '0.875rem',  // 14px (default)
                large: '1rem'        // 16px
            }
            document.documentElement.style.setProperty('--table-font-size', rootFontSizes[size])
            // Save to localStorage
            localStorage.setItem('tableFontSize', size)
        },

        loadSettings() {
            const savedSize = localStorage.getItem('tableFontSize')
            if (savedSize) {
                this.setTableFontSize(savedSize)
            } else {
                this.setTableFontSize('medium')
            }
        }
    }
})
