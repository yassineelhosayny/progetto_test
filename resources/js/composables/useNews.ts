import { ref, computed } from 'vue'

export type Localized = string | Record<string, string>

export interface NewsItem {
  id?: number
  title: string
  slug?: string
  url?: string
  featured?: boolean
  excerpt_image?: string | null
  category?: string | null
  published_at?: string | undefined
  excerpt?: string | null
  description?: string | null
  tags_array?: string[]
}

export interface Page {
  id?: number
  title?: Localized
  slug?: string
  description?: Localized
  content?: Localized
  template?: string
}

export function useNews(opts: {
  page?: Page
  news?: NewsItem[]
  featuredNews?: NewsItem[]
  categories?: string[]
  locale?: string
} = {}) {
  const locale = opts.locale || 'it'

  const getLocalizedValue = (value?: Localized): string => {
    if (!value) return ''
    if (typeof value === 'string') return value
    return (value as Record<string, string>)[locale] || (value as Record<string, string>)['it'] || Object.values(value)[0] || ''
  }

  const title = computed(() => getLocalizedValue(opts.page?.title))
  const description = computed(() => getLocalizedValue(opts.page?.description))
  const content = computed(() => getLocalizedValue(opts.page?.content))
  const metaTitle = computed(() => title.value)
  const metaDescription = computed(() => description.value)

  // Support both plain array and Laravel paginator (Inertia) shape { data: [...], meta:..., links: [...] }
  const isPaginator = !!(opts.news && (opts.news as any).data)
  const originalNews = ref<NewsItem[]>(isPaginator ? (opts.news as any).data || [] : (opts.news as any) || [])
  const filteredNews = ref<NewsItem[]>([])
  const pagination = ref<any>(isPaginator ? opts.news : null)

  // Localize fields inside each news item (title, excerpt_or_description, category name)
  function localizeField(value?: Localized) {
    if (!value) return ''
    if (typeof value === 'string') return value
    return (value as Record<string, string>)[locale] || (value as Record<string, string>)['it'] || Object.values(value)[0] || ''
  }

  function localizeItem(raw: any): NewsItem {
    const item: any = { ...raw }
    // title may be localized object
    item.title = localizeField(raw.title)
    // excerpt_or_description may be localized
    item.excerpt_or_description = localizeField(raw.excerpt_or_description)
    // category can be object { id, name: {it, en}, slug } or string
    if (raw.category && typeof raw.category === 'object') {
      item.category = localizeField(raw.category.name || raw.category)
    } else {
      item.category = raw.category || null
    }
    return item as NewsItem
  }

  // Initialize localized lists
  const localizedOriginal = ref<NewsItem[]>(originalNews.value.map(localizeItem))
  filteredNews.value = localizedOriginal.value.slice()
  const featuredNews = ref<NewsItem[]>(opts.featuredNews || [])
  const categories = ref<string[]>(opts.categories || [])

  const searchQuery = ref<string>('')
  const activeCategory = ref<string | null>(null)
  const showOnlyFeatured = ref(false)

  function formatDate(iso?: string | null) {
    if (!iso) return ''
    try {
      const d = new Date(iso)
      return d.toLocaleDateString()
    } catch (e) {
      return String(iso)
    }
  }

  function getImageUrl(path?: string | null) {
    if (!path) return ''
    // if (path.startsWith('/')) return path
    return `/storage/${path}`
  }

  function limit(text?: string | null, n = 120) {
    if (!text) return ''
    return text.length > n ? text.slice(0, n).trim() + '…' : text
  }

  function onSearch() {
    const q = searchQuery.value.trim().toLowerCase()
    let items = (originalNews.value || []) as NewsItem[]
    if (q) items = items.filter(i => (i.title || '').toLowerCase().includes(q) || (i.excerpt_or_description || '').toLowerCase().includes(q))
    if (activeCategory.value) items = items.filter(i => i.category === activeCategory.value)
    if (showOnlyFeatured.value) items = items.filter(i => i.featured)
    filteredNews.value = items
  }

  function clearFilters() {
    searchQuery.value = ''
    activeCategory.value = null
    showOnlyFeatured.value = false
    filteredNews.value = originalNews.value.slice()
  }

  return {
    title,
    description,
    content,
    metaTitle,
    metaDescription,
    news: filteredNews,
    featuredNews,
    categories,
    searchQuery,
    activeCategory,
    showOnlyFeatured,
    onSearch,
    clearFilters,
    formatDate,
    getImageUrl,
    limit,
    pagination,
  }
}
