let dearflipPromise: Promise<void> | null = null

function loadScript(src: string): Promise<void> {
  return new Promise((resolve, reject) => {
    const existing = document.querySelector(`script[src="${src}"]`)
    if (existing) return resolve()

    const script = document.createElement('script')
    script.src = src
    script.async = false
    script.onload = () => resolve()
    script.onerror = () => reject(new Error(`Errore caricando ${src}`))
    document.body.appendChild(script)
  })
}

function loadStyle(href: string): Promise<void> {
  return new Promise((resolve, reject) => {
    const existing = document.querySelector(`link[href="${href}"]`)
    if (existing) return resolve()

    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = href
    link.onload = () => resolve()
    link.onerror = () => reject(new Error(`Errore caricando ${href}`))
    document.head.appendChild(link)
  })
}

export async function loadDearflip(): Promise<void> {
  if (typeof window === 'undefined') return

  if (!dearflipPromise) {
    dearflipPromise = (async () => {
      await loadStyle('/vendor/dflip/css/dflip.min.css')

      // opzionale: da includere solo se ti serve davvero con la tua build/versione
      // await loadStyle('/vendor/dflip/css/themify-icons.min.css')

      await loadScript('/vendor/dflip/js/libs/jquery.min.js')
      await loadScript('/vendor/dflip/js/dflip.min.js')
    })()
  }

  return dearflipPromise
}