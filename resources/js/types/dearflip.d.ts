export {}

declare global {
  interface Window {
    jQuery?: any
    $?: any
    DFLIP?: any
    [key: string]: any
  }
}