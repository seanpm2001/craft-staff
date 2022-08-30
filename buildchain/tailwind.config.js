module.exports = {
    content: [
        './src/**/*.{vue,js,ts}',
    ],
    safelist: [
        'bg-indigo-600',
        'hover:bg-indigo-600',
        'border',
        'border-r',
        'border-b-0',
        'border-l-0',
        'border-r-0',
        'border-gray-200',
        'box-border',
        'col-span-3',
        'col-span-4',
        'md:col-span-2',
        'flex-col',
        'font-light',
        'font-normal',
        'gap-4',
        'gap-6',
        'gap-x-4',
        'gap-y-2',
        'grid-cols-3',
        'grid-cols-4',
        'grid-cols-5',
        'grid-cols-6',
        'sm:grid-cols-3',
        'sm:grid-cols-4',
        'sm:grid-cols-5',
        'sm:grid-cols-6',
        'md:grid-cols-2',
        'lg:grid-cols-3',
        'lg:grid-cols-4',
        'lg:grid-cols-5',
        'lg:grid-cols-6',
        'h-6',
        'italic',
        'left-full',
        'line-through',
        'm-0',
        'mb-2',
        'mb-4',
        'mb-6',
        '-ml-6',
        'mr-0',
        '-mt-2.5',
        'mt-0',
        'mt-1',
        'p-1',
        'p-6',
        'pb-6',
        'pl-6',
        'pl-px',
        'pr-1',
        'pt-6',
        'pt-10',
        'px-6',
        'py-4',
        'py-6',
        'rounded-lg',
        'rounded-sm',
        'text-4xl',
        'text-blue-600',
        'text-indigo-600',
        'text-indigo-900',
        'hover:text-indigo-800',
        'w-6',
    ],
    theme: {

        // Extend the default Tailwind config here
        extend: {
            colors: {
                gray: {
                    50: '#fbfcfe',
                    500: '#607d9f',
                    800: '#33404d',
                }
            },
            display: {
                'hide': 'display:none'
            },
        },

    },
    important: true,
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/aspect-ratio'),
    ],
};