import { create } from 'zustand';

export const useReportStore = create((set) => ({
    step: 'form', // 'form' | 'map' | 'success'
    reportData: {
        title: '',
        location: null, // { lat, lng }
        category: '',
        description: '',
        imagePreview: null,
        imageFile: null,
    },
    setStep: (step) => set({ step }),
    updateReportData: (data) =>
        set((state) => ({
            reportData: { ...state.reportData, ...data },
        })),
    resetReport: () =>
        set({
            step: 'form',
            reportData: {
                title: '',
                location: null,
                category: '',
                description: '',
                imagePreview: null,
            },
        }),
}));
