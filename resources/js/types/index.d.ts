export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string;
}

export interface Page {
  id: number;
  title: string | Record<string, string>;
  slug: string;
  description?: string | Record<string, string>;
  content?: string | Record<string, string>;
  template: string;
  blocks?: Block[];
  meta_title?: string | Record<string, string>;
  meta_description?: string | Record<string, string>;
  is_homepage: boolean;
  status: 'draft' | 'published';
  created_at: string;
  updated_at: string;
}

export interface Block {
  type: string;
  data: Record<string, any>;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User | null;
  };
  locale: string;
  locales: Record<string, { native: string; flag: string }>;
};
