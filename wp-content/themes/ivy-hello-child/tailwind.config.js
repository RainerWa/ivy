module.exports = {
  content: [
    './**/*.php',
    './src/**/*.css',
    './src/**/*.scss'
  ],
  theme: {
    extend: {
      colors: {
        bg: 'var(--color-bg)',
        text: 'var(--color-text)',
        primary: 'var(--color-primary)',
        button: 'var(--color-button)',
      },
      maxWidth: {
        'content': 'var(--max-width-content)',
      },
    },
  },
  plugins: [],
}

