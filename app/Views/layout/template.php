<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DataSatu.com | Vocational Learning Center' ?></title>
    
    <!-- Google Fonts: Nunito Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,300..900;1,6..12,300..900&display=swap" rel="stylesheet">
    
    <!-- Full Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['"Nunito Sans"', 'sans-serif'],
                        nunito: ['"Nunito Sans"', 'sans-serif'],
                        poppins: ['"Nunito Sans"', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#C41E24',
                            dark: '#8E1418',
                            light: '#E23744',
                        },
                        accent: {
                            DEFAULT: '#F5841F',
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
<body class="text-gray-800 bg-white antialiased selection:bg-brand selection:text-white">

    <?= $this->include('layout/header') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layout/footer') ?>

</body>
</html>
