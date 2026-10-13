{{-- Contact Form Component --}}
<div class="contact-form {{ $form['wrapper_classes'] ?? 'bg-white p-8 rounded-lg shadow-lg' }}"
     x-data="contactForm()">
    
    {{-- Form Title --}}
    @if(isset($form['title']) && $form['title'])
        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $form['title'] }}</h3>
    @endif

    {{-- Form Description --}}
    @if(isset($form['description']) && $form['description'])
        <p class="text-center">{{ $form['description'] }}</p>
    @endif

    {{-- Success Message --}}
    <div x-show="showSuccess" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6"
         style="display: none;">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span x-text="successMessage"></span>
        </div>
    </div>

    {{-- Error Message --}}
    <div x-show="showError" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6"
         style="display: none;">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span x-text="errorMessage"></span>
        </div>
    </div>

    {{-- Form --}}
    <form @submit.prevent="submitForm()" class="space-y-6">
        {{-- Name Field --}}
        <div class="form-group">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                {{ $form['labels']['name'] ?? 'Nome' }} *
            </label>
            <input type="text" 
                   id="name" 
                   name="name"
                   x-model="formData.name"
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                   :class="{'border-red-300': errors.name}"
                   placeholder="{{ $form['placeholders']['name'] ?? 'Inserisci il tuo nome' }}">
            <p x-show="errors.name" x-text="errors.name" class="text-red-600 text-sm mt-1"></p>
        </div>

        {{-- Email Field --}}
        <div class="form-group">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                {{ $form['labels']['email'] ?? 'Email' }} *
            </label>
            <input type="email" 
                   id="email" 
                   name="email"
                   x-model="formData.email"
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                   :class="{'border-red-300': errors.email}"
                   placeholder="{{ $form['placeholders']['email'] ?? 'Inserisci la tua email' }}">
            <p x-show="errors.email" x-text="errors.email" class="text-red-600 text-sm mt-1"></p>
        </div>

        {{-- Phone Field (if enabled) --}}
        @if(isset($form['fields']['phone']) && $form['fields']['phone'])
            <div class="form-group">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $form['labels']['phone'] ?? 'Telefono' }}
                </label>
                <input type="tel" 
                       id="phone" 
                       name="phone"
                       x-model="formData.phone"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                       placeholder="{{ $form['placeholders']['phone'] ?? 'Inserisci il tuo numero di telefono' }}">
            </div>
        @endif

        {{-- Subject Field (if enabled) --}}
        @if(isset($form['fields']['subject']) && $form['fields']['subject'])
            <div class="form-group">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $form['labels']['subject'] ?? 'Oggetto' }}
                </label>
                <input type="text" 
                       id="subject" 
                       name="subject"
                       x-model="formData.subject"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                       placeholder="{{ $form['placeholders']['subject'] ?? 'Inserisci l\'oggetto del messaggio' }}">
            </div>
        @endif

        {{-- Message Field --}}
        <div class="form-group">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                {{ $form['labels']['message'] ?? 'Messaggio' }} *
            </label>
            <textarea id="message" 
                      name="message"
                      x-model="formData.message"
                      required
                      rows="5"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none"
                      :class="{'border-red-300': errors.message}"
                      placeholder="{{ $form['placeholders']['message'] ?? 'Scrivi il tuo messaggio...' }}"></textarea>
            <p x-show="errors.message" x-text="errors.message" class="text-red-600 text-sm mt-1"></p>
        </div>

        {{-- Privacy Checkbox --}}
        @if(isset($form['privacy']) && $form['privacy'])
            <div class="form-group">
                <label class="flex items-start">
                    <input type="checkbox" 
                           x-model="formData.privacy"
                           required
                           class="mt-1 mr-3 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">
                        {{ $form['privacy']['text'] ?? 'Accetto la' }}
                        @if(isset($form['privacy']['url']) && $form['privacy']['url'])
                            <a href="{{ $form['privacy']['url'] }}" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 underline">
                                {{ $form['privacy']['link_text'] ?? 'Privacy Policy' }}
                            </a>
                        @else
                            {{ $form['privacy']['link_text'] ?? 'Privacy Policy' }}
                        @endif
                    </span>
                </label>
            </div>
        @endif

        {{-- Submit Button --}}
        <div class="form-group">
            <button type="submit" 
                    :disabled="isSubmitting"
                    class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isSubmitting">{{ $form['submit_text'] ?? 'Invia Messaggio' }}</span>
                <span x-show="isSubmitting" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $form['submitting_text'] ?? 'Invio in corso...' }}
                </span>
            </button>
        </div>
    </form>
</div>

<script>
function contactForm() {
    return {
        formData: {
            name: '',
            email: '',
            phone: '',
            subject: '',
            message: '',
            privacy: false
        },
        errors: {},
        isSubmitting: false,
        showSuccess: false,
        showError: false,
        successMessage: '',
        errorMessage: '',
        
        async submitForm() {
            this.clearMessages();
            this.validateForm();
            
            if (Object.keys(this.errors).length > 0) {
                return;
            }
            
            this.isSubmitting = true;
            
            try {
                const response = await fetch('/contact', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.formData)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    this.showSuccessMessage(result.message || 'Messaggio inviato con successo!');
                    this.resetForm();
                } else {
                    this.showErrorMessage(result.message || 'Errore durante l\'invio del messaggio.');
                }
                
            } catch (error) {
                this.showErrorMessage('Errore di connessione. Riprova più tardi.');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        validateForm() {
            this.errors = {};
            
            if (!this.formData.name.trim()) {
                this.errors.name = 'Il nome è obbligatorio';
            }
            
            if (!this.formData.email.trim()) {
                this.errors.email = 'L\'email è obbligatoria';
            } else if (!this.isValidEmail(this.formData.email)) {
                this.errors.email = 'Inserisci un\'email valida';
            }
            
            if (!this.formData.message.trim()) {
                this.errors.message = 'Il messaggio è obbligatorio';
            }
        },
        
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccess = true;
            setTimeout(() => {
                this.showSuccess = false;
            }, 5000);
        },
        
        showErrorMessage(message) {
            this.errorMessage = message;
            this.showError = true;
            setTimeout(() => {
                this.showError = false;
            }, 5000);
        },
        
        clearMessages() {
            this.showSuccess = false;
            this.showError = false;
            this.errors = {};
        },
        
        resetForm() {
            this.formData = {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: '',
                privacy: false
            };
        }
    }
}
</script>