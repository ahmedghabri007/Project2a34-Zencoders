/**
 * Enhanced Translation Manager for ZenCoders Forum
 * Handles translation, text-to-speech, and speech-to-text functionality
 */

// Global variables
let recognition;

document.addEventListener('DOMContentLoaded', function() {
    initTranslation();
});

/**
 * Initialize the translation functionality
 */
function initTranslation() {
    console.log('Initializing translation functionality');
    // Set up language selector
    const languageSelector = document.getElementById('language-selector');
    
    if (languageSelector) {
        // Set default language from localStorage or default to English
        const preferredLanguage = localStorage.getItem('preferredLanguage') || 'en';
        languageSelector.value = preferredLanguage;
        
        // Add change event listener
        languageSelector.addEventListener('change', function() {
            localStorage.setItem('preferredLanguage', this.value);
            // Reset all translations when language changes
            resetAllTranslations();
            
            // Update speech recognition language
            if (recognition) {
                // Set the speech recognition language based on the selected language
                switch(this.value) {
                    case 'fr':
                        recognition.lang = 'fr-FR';
                        break;
                    case 'es':
                        recognition.lang = 'es-ES';
                        break;
                    case 'de':
                        recognition.lang = 'de-DE';
                        break;
                    case 'it':
                        recognition.lang = 'it-IT';
                        break;
                    case 'zh':
                        recognition.lang = 'zh-CN';
                        break;
                    case 'ar':
                        recognition.lang = 'ar-SA';
                        break;
                    default:
                        recognition.lang = 'en-US';
                }
            }
        });
    }
    
    // Set up translation buttons
    setupTranslationButtons();
    
    // Set up speech recognition
    setupSpeechRecognition();
}

/**
 * Set up the translation buttons
 */
function setupTranslationButtons() {
    console.log('Setting up translation buttons');
    const translateButtons = document.querySelectorAll('.translate-btn');
    console.log('Found', translateButtons.length, 'translation buttons');
    
    translateButtons.forEach(button => {
        // Remove existing event listeners to avoid duplicates
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function() {
            const contentId = this.getAttribute('data-content-id');
            const contentType = this.getAttribute('data-content-type');
            
            console.log('Translation button clicked for', contentType, contentId);
            
            // Get the original and translated content elements
            const originalContent = document.getElementById(`${contentType}-${contentId}`);
            const translatedContent = document.getElementById(`${contentType}-translated-${contentId}`);
            
            console.log('Original content:', originalContent);
            console.log('Translated content:', translatedContent);
            
            if (!originalContent || !translatedContent) {
                console.error('Could not find content elements');
                return;
            }
            
            // Toggle between original and translated content
            if (translatedContent.style.display === 'block') {
                console.log('Showing original content');
                // Show original
                originalContent.style.display = 'block';
                translatedContent.style.display = 'none';
                this.innerHTML = '<i class="ti ti-language"></i> Translate';
                this.classList.remove('btn-success');
                this.classList.add('btn-primary');
            } else {
                console.log('Showing/generating translated content');
                // Show translated or translate
                originalContent.style.display = 'none';
                translatedContent.style.display = 'block';
                
                // Check if we already have a translation
                if (translatedContent.getAttribute('data-translated') === 'true') {
                    console.log('Using existing translation');
                    // We already have a translation, just show it
                    this.innerHTML = '<i class="ti ti-language"></i> Show Original';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                } else {
                    console.log('Generating new translation');
                    // Need to translate
                    translateContent(originalContent.textContent, translatedContent, this);
                }
            }
        });
    });
    
    // Set up text-to-speech buttons
    setupTextToSpeechButtons();
}

/**
 * Set up text-to-speech buttons
 */
function setupTextToSpeechButtons() {
    console.log('Setting up text-to-speech buttons');
    const textToSpeechButtons = document.querySelectorAll('.text-to-speech-btn');
    console.log('Found', textToSpeechButtons.length, 'text-to-speech buttons');
    
    textToSpeechButtons.forEach(button => {
        // Remove existing event listeners to avoid duplicates
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function() {
            const textToSpeak = this.getAttribute('data-text');
            const language = document.getElementById('language-selector').value;
            speakText(textToSpeak, language);
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
    // Add console logging for debugging
    console.log('Translating text:', text);
    console.log('Target element:', targetElement);
    console.log('Button:', button);
    
    // Store the original button text
    const originalButtonText = button.innerHTML;
    
    // Show loading animation
    targetElement.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Translating...</span>
        </div>
    `;
    
    // Update button to show loading state
    button.innerHTML = `<i class="ti ti-loader ti-spin"></i> Translating...`;
    
    // Get selected language
    const language = document.getElementById('language-selector').value;
    
    // Make AJAX request to translate the text
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
            // Show the translated content
            targetElement.textContent = data.translation;
            targetElement.setAttribute('data-translated', 'true');
            targetElement.style.display = 'block'; // Make sure it's visible
            
            // Apply RTL for Arabic
            if (language === 'ar') {
                targetElement.dir = 'rtl';
                targetElement.classList.add('arabic-text');
            } else {
                targetElement.dir = 'ltr';
                targetElement.classList.remove('arabic-text');
            }
            
            // Update button state
            button.innerHTML = '<i class="ti ti-language"></i> Show Original';
            button.classList.remove('btn-primary');
            button.classList.add('btn-success');
        } else {
            // Show error
            targetElement.innerHTML = `
                <div class="alert alert-danger">
                    Translation failed. Please try again.
                </div>
            `;
            targetElement.style.display = 'block';
            
            // Restore button state
            button.innerHTML = '<i class="ti ti-language"></i> Translate';
            button.classList.remove('btn-success');
            button.classList.add('btn-primary');
        }
    })
    .catch(error => {
        // Show error
        targetElement.innerHTML = `
            <div class="alert alert-danger">
                Error connecting to translation service.
            </div>
        `;
        targetElement.style.display = 'block';
        
        // Restore button state
        button.innerHTML = '<i class="ti ti-language"></i> Translate';
        button.classList.remove('btn-success');
        button.classList.add('btn-primary');
        
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
        btn.innerHTML = '<i class="ti ti-language"></i> Translate';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
    });
}

/**
 * Text-to-speech functionality
 * 
 * @param {string} text - Text to speak
 * @param {string} language - Language code
 */
function speakText(text, language) {
    if ('speechSynthesis' in window) {
        // Stop any ongoing speech
        window.speechSynthesis.cancel();
        
        // Create a new speech synthesis utterance
        const utterance = new SpeechSynthesisUtterance(text);
        
        // Set language based on the selected language
        switch(language) {
            case 'fr':
                utterance.lang = 'fr-FR';
                break;
            case 'es':
                utterance.lang = 'es-ES';
                break;
            case 'de':
                utterance.lang = 'de-DE';
                break;
            case 'it':
                utterance.lang = 'it-IT';
                break;
            case 'zh':
                utterance.lang = 'zh-CN';
                break;
            case 'ar':
                utterance.lang = 'ar-SA';
                break;
            default:
                utterance.lang = 'en-US';
        }
        
        // Speak the text
        window.speechSynthesis.speak(utterance);
    } else {
        alert('Text-to-speech is not supported in your browser.');
    }
}

/**
 * Setup speech recognition functionality
 */
function setupSpeechRecognition() {
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        // Initialize speech recognition
        recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.continuous = false;
        recognition.interimResults = true;
        
        // Get the selected language
        const languageSelector = document.getElementById('language-selector');
        if (languageSelector) {
            // Set the speech recognition language based on the translation language
            switch(languageSelector.value) {
                case 'fr':
                    recognition.lang = 'fr-FR';
                    break;
                case 'es':
                    recognition.lang = 'es-ES';
                    break;
                case 'de':
                    recognition.lang = 'de-DE';
                    break;
                case 'it':
                    recognition.lang = 'it-IT';
                    break;
                case 'zh':
                    recognition.lang = 'zh-CN';
                    break;
                case 'ar':
                    recognition.lang = 'ar-SA';
                    break;
                default:
                    recognition.lang = 'en-US';
            }
        } else {
            recognition.lang = 'en-US'; // Default language
        }
        
        recognition.onresult = function(event) {
            const transcript = Array.from(event.results)
                .map(result => result[0])
                .map(result => result.transcript)
                .join('');
            
            const targetId = recognition.targetInputId;
            if (targetId) {
                const targetInput = document.getElementById(targetId);
                if (targetInput) {
                    targetInput.value += transcript;
                }
            }
        };
        
        recognition.onerror = function(event) {
            console.error('Speech recognition error', event.error);
            stopSpeechRecognition();
        };
        
        recognition.onend = function() {
            const micButtons = document.querySelectorAll('.speech-to-text-btn');
            micButtons.forEach(btn => {
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-secondary');
            });
        };
    }
}

/**
 * Start speech recognition for a specific input field
 * 
 * @param {string} targetInputId - ID of the input field to receive the speech text
 */
function startSpeechRecognition(targetInputId) {
    if (recognition) {
        // Stop any ongoing recognition
        stopSpeechRecognition();
        
        // Set the target input field
        recognition.targetInputId = targetInputId;
        
        // Start listening
        recognition.start();
        
        // Update button appearance
        const micButtons = document.querySelectorAll('.speech-to-text-btn');
        micButtons.forEach(btn => {
            if (btn.dataset.target === targetInputId) {
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-danger');
            }
        });
    } else {
        alert('Speech recognition is not supported in your browser.');
    }
}

/**
 * Stop speech recognition
 */
function stopSpeechRecognition() {
    if (recognition) {
        recognition.stop();
    }
}
