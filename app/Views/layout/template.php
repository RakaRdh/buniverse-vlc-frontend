<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DataSatu.com | Vocational Learning Center' ?></title>
    
    <!-- Google Fonts: Nunito Sans (Full Weights 300 to 900) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Full Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['"Nunito Sans"', 'sans-serif'],
                        nunito: ['"Nunito Sans"', 'sans-serif'],
                    },
                    fontSize: {
                        'h1': ['32px', { lineHeight: '1.25' }],
                        'h2': ['24px', { lineHeight: '1.3' }],
                        'h3': ['20px', { lineHeight: '1.4' }],
                        'h4': ['16px', { lineHeight: '1.5' }],
                        'h5': ['14px', { lineHeight: '1.5' }],
                        'small': ['12px', { lineHeight: '1.5' }],
                        'xsmall': ['10px', { lineHeight: '1.5' }],
                    },
                    fontWeight: {
                        'light': '300',
                        'normal': '400',
                        'regular': '400',
                        'medium': '500',
                        'semibold': '600',
                        'bold': '700',
                        'extrabold': '800',
                        'xbold': '800',
                        'black': '900',
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#C41E24',
                            dark: '#8E1418',
                            light: '#E23744',
                        },
                        accent: {
                            DEFAULT: '#FF8D28',
                            dark: '#DB6E10',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }
        body, * { 
            font-family: 'Nunito Sans', sans-serif !important; 
        }
    </style>
</head>
<body class="text-gray-800 bg-white antialiased selection:bg-brand selection:text-white overflow-x-hidden w-full relative">

    <?= $this->include('layout/header') ?>

    <main class="overflow-x-hidden w-full">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layout/footer') ?>

</body>
</html>
