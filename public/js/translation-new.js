/**
 * Enhanced Translation Manager for ZenCoders Forum
 */
document.addEventListener('DOMContentLoaded', function() {
    initTranslation();
});

/**
 * Initialize the translation functionality
 */
function initTranslation() {
    // Set up language selector
    const languageSelector = document.getElementById('language-selector');
    if (languageSelector) {
        // Set initial value from localStorage
        const savedLanguage = localStorage.getItem('preferredLanguage') || 'fr';
        languageSelector.value = savedLanguage;
        
        // Add change event listener
        languageSelector.addEventListener('change', function() {
            localStorage.setItem('preferredLanguage', this.value);
            // Reset all translations when language changes
            resetAllTranslations();
        });
    }
    
    // Set up translation buttons
    setupTranslationButtons();
}

/**
 * Set up the translation buttons
 */
function setupTranslationButtons() {
    const translateButtons = document.querySelectorAll('.translate-btn');
    
    translateButtons.forEach(button => {
        // Remove existing event listeners to avoid duplicates
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function() {
            const contentId = this.getAttribute('data-content-id');
            const contentType = this.getAttribute('data-content-type');
            
            const originalContent = document.getElementById(`${contentType}-${contentId}`);
            const translatedContent = document.getElementById(`${contentType}-translated-${contentId}`);
            
            if (!originalContent || !translatedContent) return;
            
            // Toggle between original and translated content
            if (translatedContent.style.display === 'block') {
                // Show original
                originalContent.style.display = 'block';
                translatedContent.style.display = 'none';
                this.innerHTML = '<i class="fas fa-language"></i> Translate';
                this.classList.remove('btn-success');
                this.classList.add('btn-primary');
            } else {
                // Show translated or translate
                originalContent.style.display = 'none';
                translatedContent.style.display = 'block';
                
                // Check if we already have a translation
                if (translatedContent.getAttribute('data-translated') === 'true') {
                    // We already have a translation, just show it
                    this.innerHTML = '<i class="fas fa-language"></i> Show Original';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                } else {
                    // Need to translate
                    translateContent(originalContent.textContent, translatedContent, this);
                }
            }
        });
    });
}

/**
 * Translate content and update the target element
 * 
 * @param {string} text - Text to translate
 * @param {HTMLElement} targetElement - Element to update with translated text
 * @param {HTMLElement} button - Button that triggered the translation
 */
function translateContent(text, targetElement, button) {
    // Show loading animation
    targetElement.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Translating...</span>
        </div>
    `;
    
    // Get selected language
    const language = document.getElementById('language-selector').value;
    
    // Make API call to translate
    fetch('/project-2a34/index.php?action=translate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `text=${encodeURIComponent(text)}&language=${encodeURIComponent(language)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update with translation
            targetElement.textContent = data.translation;
            targetElement.setAttribute('data-translated', 'true');
            
            // Apply RTL for Arabic
            if (language === 'ar') {
                targetElement.dir = 'rtl';
                targetElement.classList.add('arabic-text');
            } else {
                targetElement.dir = 'ltr';
                targetElement.classList.remove('arabic-text');
            }
            
            // Update button
            button.innerHTML = '<i class="fas fa-language"></i> Show Original';
            button.classList.remove('btn-primary');
            button.classList.add('btn-success');
        } else {
            // Show error
            targetElement.innerHTML = `
                <div class="alert alert-danger">
                    Translation failed. Please try again.
                </div>
            `;
        }
    })
    .catch(error => {
        // Show error
        targetElement.innerHTML = `
            <div class="alert alert-danger">
                Error connecting to translation service.
            </div>
        `;
        console.error('Translation error:', error);
    });
}

/**
 * Reset all translations when language changes
 */
function resetAllTranslations() {
    // Reset all translated content
    document.querySelectorAll('[id^="content-translated-"], [id^="comment-translated-"]').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
        el.removeAttribute('data-translated');
    });
    
    // Show all original content
    document.querySelectorAll('[id^="content-"][id$="-translated"]').forEach(el => {
        const originalId = el.id.replace('-translated', '');
        const originalElement = document.getElementById(originalId);
        if (originalElement) {
            originalElement.style.display = 'block';
        }
    });
    
    // Reset all buttons
    document.querySelectorAll('.translate-btn').forEach(btn => {
        btn.innerHTML = '<i class="fas fa-language"></i> Translate';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
    });
}
