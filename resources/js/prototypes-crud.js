// resources/js/prototypes-crud.js

export default function prototypesCRUD() {
    return {
        isModalOpen: false,
        isEditMode: false,
        isDeleteModalOpen: false,
        deleteUrl: '',
        prototypeToDeleteName: '',
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
            this.formTitle = '[ FILE NEW PROTOTYPE DOSSIER ]';
            this.formAction = '/prototypes'; // Use a static URL, as route() is PHP
            this.formSubmitButton = 'SUBMIT DOSSIER';
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
            this.formTitle = '[ EDIT PROTOTYPE DOSSIER ]';
            this.formAction = `/prototypes/${prototype.id}`; // Build the update URL
            this.formSubmitButton = 'UPDATE DOSSIER';
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
        }
    };
}