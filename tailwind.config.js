module.exports = {
    purge: [
        './resources/**/*.blade.php',
        './resources/webbuilder/**/*.blade.php',
    ],
    theme: {
        fontFamily: {
            "headings": ['Poppins'],
            "navigation": ['Titillium Web'],
            "main": ['Roboto Slab']
         },
        extend: {
            spacing: {
                '0.5': '0.125rem',
                '1.5': '0.375rem',
                '2.5': '0.625rem',
                '3.5': '0.875rem',
                '7':   '1.75rem',
                '9':   '2.25rem',
                '11':  '2.75rem',
                '14':  '3.5rem',
            }
        }
  },
  variants: {},
  plugins: []
}
