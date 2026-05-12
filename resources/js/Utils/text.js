import { ref, onMounted, onBeforeUnmount } from 'vue'

export function useScrollProgress() {
    const progress = ref(0)
  
    const updateProgress = () => {
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop
  
      const docHeight =
        document.documentElement.scrollHeight -
        document.documentElement.clientHeight
  
      progress.value = docHeight > 0
        ? Math.min(Math.round((scrollTop / docHeight) * 100), 100)
        : 0
    }
  
    onMounted(() => {
      updateProgress()
      window.addEventListener('scroll', updateProgress, { passive: true })
    })
  
    onBeforeUnmount(() => {
      window.removeEventListener('scroll', updateProgress)
    })
  
    return {
      progress
    }
  }
  
export const limitWords = (text, limit = 10) => {
    if (!text) return ''
    const words = text.trim().split(/\s+/)
    return words.length > limit ? words.slice(0, limit).join(' ') + '…' : text
}

export function limitChars(text, limit = 100, suffix = '...') {
  if (!text) return ''

  const str = String(text)

  if (str.length <= limit) return str

  return str.substring(0, limit) + suffix
}

export function limitText(text, limit = 100, suffix = '...') {
  if (!text) return ''

  const clean = text.replace(/<[^>]*>?/gm, '')
  if (clean.length <= limit) return clean

  return clean.substring(0, limit) + suffix
}


export function readingTime(text, wpm = 200) {
    if (!text) return { minutes: 0, words: 0}

    const words = text
        .replace(/<[^>]*>/g, '') // remove tag
        .trim()
        .split(/\s+/).length
        
    const minutes = Math.max(1, Math.ceil(words / wpm))

    return minutes
}
  
export function smartWordwrap(str, width = 75, breakStr = "\n") {
    // Pattern for words longer than width
    const pattern = new RegExp(`([^ ]{${width},})`, "g");

    let output = "";
    let words = str.split(pattern);

    words.forEach(word => {
        if (word.includes(" ")) {
            // Normal words: append
            output += word;
        } else {
            // Long unbroken word
            const wrappedLines = output.split(breakStr);
            const lastLine = wrappedLines[wrappedLines.length - 1];
            const count = width - (lastLine.length % width);

            // Fill current line
            output += word.substring(0, count) + breakStr;

            // Wrap remaining part of long word
            const remaining = word.substring(count);
            output += wrapLongWord(remaining, width, breakStr);
        }
    });

    return finalWrap(output, width, breakStr);
}

export function stripHtml(html = '') {
    if (typeof html !== 'string') return ''
  
    // Create a temporary DOM element
    const div = document.createElement('div')
    div.innerHTML = html
  
    // Get text content (preserves spaces & line breaks better)
    return div.textContent || div.innerText || ''
  }

// Helper: break long word into chunks
function wrapLongWord(str, width, breakStr) {
    let result = "";
    for (let i = 0; i < str.length; i += width) {
        result += str.substring(i, i + width) + breakStr;
    }
    return result;
}

// Final wrap using normal behavior
function finalWrap(str, width, breakStr) {
    const regex = new RegExp(`(.{1,${width}})( +|$)`, "g");
    return str.match(regex).join(breakStr).trim();
}


export function formatUS(date) {
    const d = new Date(date);
    return d.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "2-digit",
    });
}