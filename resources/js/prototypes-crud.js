// resources/js/prototypes-crud.js

export default function prototypesCRUD() {
    return {
        isModalOpen: false,
        isEditMode: false,
        formTitle: '',
        formAction: '',
        formSubmitButton: '',
        formData: {
            name: '',
            codename: '',
            project_type: '',
            status: 'PLANNED',
            description: '',
            tech_stack: '',
            repository_url: '',
            live_url: '',
            cover_image_path: '',
            start_date: '',
            completed_date: ''
        },

        // State untuk modal Delete
        isDeleteModalOpen: false,
        deleteFormAction: '', // URL untuk form action
        prototypeToDeleteName: '', // Nama prototype yang akan dihapus

        // Legacy support untuk variable lama (jika masih ada referensi)
        actionUrl: '', 
        itemName: '',

        // Helper function to format DB time to a string for the input field
        formatDbTimeToInput(dbDateString) {
            if (!dbDateString) return '';
            // Takes a string from Laravel (e.g., "2025-08-17 16:00:00")
            // and converts it to the format understood by datetime-local input (e.g., "2025-08-17T16:00")
            return dbDateString.replace(' ', 'T').substring(0, 16);
        },

        // Sets up the modal for creating a new prototype
        openCreateModal() {
            this.isModalOpen = true;
            this.isEditMode = false;
            this.formTitle = '[ FILE NEW PROTOTYPE PROJECT]';
            this.formAction = '/prototypes'; // Use a static URL, as route() is PHP
            this.formSubmitButton = 'SUBMIT PROJECT';
            // Reset form data to its initial state
            this.formData = { 
                name: '', codename: '', project_type: '', status: 'PLANNED', 
                description: '', tech_stack: '', repository_url: '', live_url: '', 
                cover_image_path: '', start_date: '', completed_date: '' 
            };
        },

        // Sets up the modal for editing an existing prototype
        openEditModal(prototype) {
            this.isModalOpen = true;
            this.isEditMode = true;
            this.formTitle = '[ EDIT PROTOTYPE PROJECT ]';
            this.formAction = `/prototypes/${prototype.id}`; // Build the update URL
            this.formSubmitButton = 'UPDATE PROJECT';
            this.formData = {
                name: prototype.name,
                codename: prototype.codename,
                project_type: prototype.project_type,
                status: prototype.status,
                description: prototype.description,
                tech_stack: Array.isArray(prototype.tech_stack) ? prototype.tech_stack.join(', ') : '',
                repository_url: prototype.repository_url,
                live_url: prototype.live_url,
                cover_image_path: prototype.cover_image_path,
                start_date: this.formatDbTimeToInput(prototype.start_date),
                completed_date: this.formatDbTimeToInput(prototype.completed_date)
            };
        },

        // Method untuk membuka delete modal
        openDeleteModal(actionUrl, itemName) {
            this.isDeleteModalOpen = true;
            this.deleteFormAction = actionUrl;
            this.prototypeToDeleteName = itemName;
            
            // Legacy support
            this.actionUrl = actionUrl;
            this.itemName = itemName;
            
            console.log('Delete modal opened:', { actionUrl, itemName }); // Debug
        },

         // Method untuk menutup delete modal
         closeDeleteModal() {
            this.isDeleteModalOpen = false;
            this.deleteFormAction = '';
            this.prototypeToDeleteName = '';
        },

        // Init function untuk mendaftarkan listener
        init() {
            // Listen untuk event custom
            this.$el.addEventListener('open-delete-modal', (event) => {
                this.openDeleteModal(event.detail.actionUrl, event.detail.itemName);
            });
        },
    };
}