import { reactive, ref, computed } from 'vue'
import axios from 'axios'

export function useContactForm(data: any = {}, action = '/contact') {
  const title = data.title || ''
  const description = data.description || ''

  const labels = {
    name: data?.labels?.name || 'Nome',
    last_name: data?.labels?.name || 'Cognome',
    email: data?.labels?.email || 'Email',
    phone: data?.labels?.phone || 'Telefono',
    subject: data?.labels?.subject || 'Oggetto',
    message: data?.labels?.message || 'Messaggio',
  }

  const placeholders = {
    name: data?.placeholders?.name || '',
    email: data?.placeholders?.email || '',
    message: data?.placeholders?.message || '',
  }

  const fields = {
    phone: data?.fields?.phone ?? false,
    subject: data?.fields?.subject ?? false,
  }

  const privacy = {
    enabled: data?.privacy?.enabled ?? false,
    text: data?.privacy?.text || 'Accetto la',
    link_text: data?.privacy?.link_text || 'Privacy Policy',
    url: data?.privacy?.url || '#',
  }

  const submitText = data?.submit_text || 'Invia'
  const submittingText = data?.submitting_text || 'Invio in corso...'
  const wrapperClass = data?.wrapper_classes || 'bg-white p-8 rounded-lg shadow-lg'

  const form = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
    privacy: false,
  })

  const errors = reactive<Record<string, string>>({})
  const submitting = ref(false)
  const status = ref('')

  const statusClass = computed(() => (status.value && status.value.toLowerCase().includes('inviato') ? 'text-green-600' : 'text-red-600'))

  function validate() {
    Object.keys(errors).forEach(k => (errors[k] = ''))
    let ok = true
    if (!form.first_name.trim()) { errors.first_name = 'Il nome è richiesto.'; ok = false }
    if (!form.last_name.trim()) { errors.last_name = 'Il cognome è richiesto.'; ok = false }
    if (!/\S+@\S+\.\S+/.test(form.email)) { errors.email = 'Email non valida.'; ok = false }
    if (!form.message.trim() || form.message.trim().length < 5) { errors.message = 'Il messaggio è troppo corto.'; ok = false }
    if (fields.phone && form.phone.trim().length < 6) { errors.phone = 'Telefono non valido.'; ok = false }
    if (fields.subject && !form.subject.trim()) { errors.subject = 'Oggetto richiesto.'; ok = false }
    if (privacy.enabled && !form.privacy) { errors.privacy = 'Devi accettare la privacy.'; ok = false }
    return ok
  }

  async function submit() {
    status.value = ''
    if (!validate()) return
    submitting.value = true
    try {
      const payload = { ...form }
      await axios.post(action, payload)
      status.value = 'Messaggio inviato con successo.'
      Object.keys(form).forEach(k => { (form as any)[k] = k === 'privacy' ? false : '' })
    } catch (e: any) {
      status.value = 'Errore durante l\'invio.'
      if (e.response?.data?.errors) {
        const srv = e.response.data.errors
        Object.keys(srv).forEach(k => { errors[k] = Array.isArray(srv[k]) ? srv[k].join(' ') : srv[k] })
      }
    } finally {
      submitting.value = false
    }
  }

  return {
    title,
    description,
    labels,
    placeholders,
    fields,
    privacy,
    submitText,
    submittingText,
    wrapperClass,
    form,
    errors,
    submitting,
    status,
    statusClass,
    validate,
    submit,
  }
}
