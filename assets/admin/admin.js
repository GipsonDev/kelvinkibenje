/**
 * ==============================================================================
 * CUSTOM ADMIN PANEL - JAVASCRIPT (admin.js)
 * Quill.js WYSIWYG editor binding, slug auto-generation & UI helpers
 * ==============================================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Initialize Quill.js WYSIWYG Editor if present on the page
    const editorContainer = document.getElementById('quill-editor-container');
    const hiddenContentInput = document.getElementById('post_content');

    if (editorContainer && typeof Quill !== 'undefined') {
        const quill = new Quill('#quill-editor-container', {
            theme: 'snow',
            placeholder: 'Write your inspiring article content here...',
            modules: {
                toolbar: [
                    [{ 'header': [2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'link'],
                    ['clean']
                ]
            }
        });

        // Set initial HTML content if editing an existing post
        if (hiddenContentInput && hiddenContentInput.value) {
            quill.clipboard.dangerouslyPasteHTML(hiddenContentInput.value);
        }

        // On form submit, copy Quill HTML into the hidden textarea
        const postForm = document.getElementById('postForm');
        if (postForm) {
            postForm.addEventListener('submit', function() {
                if (hiddenContentInput) {
                    hiddenContentInput.value = quill.root.innerHTML;
                }
            });
        }
    }

    // 2. Auto-generate URL slug from post title
    const titleInput = document.getElementById('post_title');
    const slugInput = document.getElementById('post_slug');

    if (titleInput && slugInput && slugInput.getAttribute('data-auto-slug') === 'true') {
        titleInput.addEventListener('input', function() {
            let text = this.value;
            let slug = text.toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove non-word chars
                .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with a hyphen
                .replace(/^-+|-+$/g, ''); // Trim leading/trailing hyphens
            slugInput.value = slug;
        });
    }

    // 3. Confirm Delete Dialogs
    const deleteButtons = document.querySelectorAll('.confirm-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm-message') || 'Are you sure you want to delete this item? This action cannot be undone.';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
