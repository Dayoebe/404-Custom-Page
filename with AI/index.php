<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom CSS for themes and effects */
        :root {
            --bg-color: #121212;
            --text-color: #E0E0E0;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --icon-color: rgba(255, 255, 255, 0.5);
            --toggle-bg: #333;
            --toggle-fg: #fff;
            --excuse-bg: rgba(255, 255, 255, 0.05);
        }

        body.light-theme {
            --bg-color: #F0F0F0;
            --text-color: #212121;
            --glass-bg: rgba(0, 0, 0, 0.05);
            --glass-border: rgba(0, 0, 0, 0.1);
            --icon-color: rgba(0, 0, 0, 0.4);
            --toggle-bg: #ccc;
            --toggle-fg: #333;
            --excuse-bg: rgba(0, 0, 0, 0.03);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            overflow: hidden; /* Prevent scrollbars from chasers going off-screen */
        }

        /* Chaser icons */
        .chaser {
            position: absolute;
            top: 0;
            left: 0;
            color: var(--icon-color);
            transition: color 0.3s ease;
            pointer-events: none; /* Make sure they don't block mouse events */
            will-change: transform; /* Performance optimization */
        }
        
        /* Glassmorphism container */
        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            transition: background 0.3s ease, border 0.3s ease;
        }

        /* Gemini-powered excuse container */
        #excuse-container {
            background-color: var(--excuse-bg);
            border: 1px solid var(--glass-border);
            transition: background-color 0.3s ease, border 0.3s ease, opacity 0.5s ease;
        }

        /* Theme Toggle Switch */
        #theme-toggle {
            background-color: var(--toggle-bg);
            transition: background-color 0.3s ease;
        }
        #theme-toggle .fa-sun, #theme-toggle .fa-moon {
             color: var(--toggle-fg);
        }
        
        body.light-theme #theme-toggle .fa-sun {
            display: none;
        }
        body:not(.light-theme) #theme-toggle .fa-moon {
            display: none;
        }
    </style>
</head>
<body class="w-screen h-screen flex items-center justify-center p-4">

    <!-- Container for chaser icons -->
    <div id="chaser-container"></div>

    <!-- Main Content -->
    <main class="relative z-10 text-center glass-container rounded-2xl shadow-lg p-8 md:p-12 max-w-lg w-full">
        <h1 class="text-6xl md:text-8xl font-bold text-indigo-400">404</h1>
        <p class="text-xl md:text-2xl font-semibold mt-4">Page Not Found</p>
        <p class="mt-4 text-base md:text-lg">Sorry, the page you are looking for does not exist or has been moved.</p>

        <!-- Gemini API Feature -->
        <div class="mt-6">
            <button id="generate-excuse" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-5 rounded-lg transition-transform transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-opacity-75">
                Tell me why ✨
            </button>
        </div>
        <div id="excuse-container" class="mt-4 p-4 rounded-lg min-h-[60px] flex items-center justify-center opacity-0 transition-opacity duration-500">
            <p id="excuse-text" class="text-base italic"></p>
            <div id="loader" class="hidden">
                <i class="fas fa-spinner fa-spin text-2xl text-teal-400"></i>
            </div>
        </div>

        <a href="#" class="mt-6 inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg transition-transform transform hover:scale-105">
            Go Home
        </a>
    </main>

    <!-- Theme Toggle -->
    <button id="theme-toggle" class="absolute top-5 right-5 w-12 h-12 flex items-center justify-center rounded-full text-xl shadow-md">
        <i class="fas fa-sun"></i>
        <i class="fas fa-moon"></i>
    </button>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chaserContainer = document.getElementById('chaser-container');
            const themeToggle = document.getElementById('theme-toggle');
            
            // Gemini feature elements
            const generateExcuseBtn = document.getElementById('generate-excuse');
            const excuseContainer = document.getElementById('excuse-container');
            const excuseText = document.getElementById('excuse-text');
            const loader = document.getElementById('loader');

            // --- Theme Toggler ---
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('light-theme');
            });

            // --- Gemini API Call ---
            generateExcuseBtn.addEventListener('click', async () => {
                // Show loader and container, clear old text
                excuseContainer.style.opacity = '1';
                excuseText.textContent = '';
                loader.classList.remove('hidden');
                generateExcuseBtn.disabled = true;

                const prompt = `Generate a short, funny, and creative excuse for why a webpage (a 404 error) could not be found. 
                The tone should be whimsical and slightly absurd. Keep it to a single sentence.
                Examples:
                - The page is currently on a secret mission to find the world's best cookie recipe.
                - Our server hamster is on a union-mandated coffee break.
                - This page was traded for three magic beans.`;

                try {
                    // This is the payload for the Gemini API call
                    let chatHistory = [{ role: "user", parts: [{ text: prompt }] }];
                    const payload = { contents: chatHistory };
                    const apiKey = ""; // The environment will provide the API key
                    const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${apiKey}`;

                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        throw new Error(`API request failed with status ${response.status}`);
                    }

                    const result = await response.json();
                    
                    let generatedText = "Could not generate an excuse. The AI is probably napping.";
                    // Safely access the generated text from the response
                    if (result.candidates && result.candidates.length > 0 &&
                        result.candidates[0].content && result.candidates[0].content.parts &&
                        result.candidates[0].content.parts.length > 0) {
                        generatedText = result.candidates[0].content.parts[0].text;
                    }
                    excuseText.textContent = `"${generatedText}"`;

                } catch (error) {
                    console.error("Error calling Gemini API:", error);
                    excuseText.textContent = "Oops! My imagination circuits are fried. Please try again.";
                } finally {
                    // Hide loader and re-enable button
                    loader.classList.add('hidden');
                    generateExcuseBtn.disabled = false;
                }
            });

            // --- Icon Chasing Logic ---
            const icons = [
                'fa-ghost', 'fa-rocket', 'fa-satellite', 'fa-robot', 'fa-star', 
                'fa-meteor', 'fa-user-astronaut', 'fa-plane', 'fa-paper-plane', 'fa-dragon'
            ];
            const chasers = [];
            const numChasers = 15;
            
            const mouse = {
                x: window.innerWidth / 2,
                y: window.innerHeight / 2
            };

            // Chaser object definition
            class Chaser {
                constructor() {
                    this.element = document.createElement('i');
                    const iconClass = icons[Math.floor(Math.random() * icons.length)];
                    this.element.className = `fas ${iconClass} chaser`;
                    
                    this.size = Math.random() * 24 + 12; // size between 12px and 36px
                    this.element.style.fontSize = `${this.size}px`;

                    this.x = Math.random() * window.innerWidth;
                    this.y = Math.random() * window.innerHeight;
                    
                    // Easing factor - lower is slower/smoother
                    this.ease = Math.random() * 0.05 + 0.02; 
                    
                    chaserContainer.appendChild(this.element);
                }

                update() {
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    this.x += dx * this.ease;
                    this.y += dy * this.ease;
                    this.element.style.transform = `translate(${this.x}px, ${this.y}px)`;
                }
            }
            
            // Create chaser instances
            for (let i = 0; i < numChasers; i++) {
                chasers.push(new Chaser());
            }
            
            // Track mouse movement
            window.addEventListener('mousemove', (e) => {
                mouse.x = e.clientX;
                mouse.y = e.clientY;
            });

            // Animation loop
            function animate() {
                chasers.forEach(chaser => chaser.update());
                requestAnimationFrame(animate);
            }

            // Start the animation
            animate();
        });
    </script>

</body>
</html>
