<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlackFile End Credits: {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400&display=swap');
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #000;
            color: #e5e7eb;
            text-align: center;
        }

        .credits-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            perspective: 400px;
        }

        .credits-scroll {
            position: absolute;
            top: -13%; /* Dimulai lebih maju supaya BLACKFILE cepat terlihat */
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 600px;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .credits-scroll.visible {
            opacity: 1;
        }

        .credit-role {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.25rem;
            color: #36d351; /* cyan-300 */
            margin-bottom: 1rem;
            margin-top: 3rem;
            text-transform: uppercase;
        }
        .credit-name {
            font-size: 1.5rem;
            color: #f3f4f6; /* gray-100 */
            margin-bottom: 0.5rem;
        }
        .logo-container {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
        }
        .logo-img {
            height: 4.5rem;
        }

        /* Loading indicator */
        .loading-indicator {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #36d351;
            font-family: 'Orbitron', sans-serif;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .loading-indicator.show {
            opacity: 1;
        }

        .fade-out {
            opacity: 0 !important;
        }
    </style>
</head>
<body class="bg-black">

    <div class="loading-indicator" id="loadingIndicator">
        INITIALIZING CREDITS...
    </div>

    <div class="credits-container">
        <div class="credits-scroll" id="credits-content">
            <div class="pt-[100vh]">
                <h1 class="text-5xl font-bold font-['Orbitron'] my-20 tracking-widest">BLACKFILE</h1>
                
                @foreach($credits as $section)
                    <div class="my-8">
                        <p class="credit-role">{{ $section->role }}</p>
                        
                        @foreach($section->names as $name)
                            <p class="credit-name">{{ $name }}</p>
                        @endforeach

                        @if(!empty($section->logos))
                            <div class="logo-container">
                                @foreach($section->logos as $logoPath)
                                    <img src="{{ url($logoPath) }}" alt="logo" class="logo-img">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="my-32 text-gray-500 text-sm">
                    <p>A BlackFile Internal Project</p>
                    <p>&copy; {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($musicCredit)
        <audio autoplay loop>
            <source src="{{ url($musicCredit->music_path) }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endif

    {{-- <button class="restart-btn" onclick="restartCredits()">RESTART CREDITS</button> --}}

    <script>
        let animationInProgress = false;

        function calculateOptimalTiming() {
            const scrollContent = document.getElementById('credits-content');
            const loadingIndicator = document.getElementById('loadingIndicator');
            
            if (!scrollContent) return;

            // Show loading indicator first
            loadingIndicator.classList.add('show');

            // Small delay to show loading, then start calculations
            setTimeout(() => {
                // Calculate content metrics
                const totalHeight = scrollContent.scrollHeight;
                const viewportHeight = window.innerHeight;
                const scrollDistance = totalHeight + viewportHeight;
                
                // Count content elements for adaptive timing
                const roles = scrollContent.querySelectorAll('.credit-role').length;
                const names = scrollContent.querySelectorAll('.credit-name').length;
                const logos = scrollContent.querySelectorAll('.logo-img').length;
                
                // Calculate timing based on content density
                // Base speed: slower for dramatic effect
                const baseSpeed = 25; // pixels per second (reduced from 20 for more drama)
                
                // Adjust speed based on content amount
                let adjustedSpeed = baseSpeed;
                
                if (names > 15) {
                    // More content = slightly faster to prevent extremely long duration
                    adjustedSpeed = baseSpeed + (names - 15) * 0.5;
                } else if (names < 5) {
                    // Less content = slower for more dramatic effect
                    adjustedSpeed = baseSpeed - 5;
                }
                
                // Ensure minimum drama (not too fast)
                adjustedSpeed = Math.max(adjustedSpeed, 15);
                // Ensure maximum practicality (not too slow)
                adjustedSpeed = Math.min(adjustedSpeed, 40);
                
                const totalDuration = scrollDistance / adjustedSpeed;
                
                // Add initial delay so BLACKFILE is visible immediately, then starts scrolling
                const initialDelay = 1; // 1 second delay before scrolling starts
                
                console.log(`Content Analysis:
                - Roles: ${roles}
                - Names: ${names} 
                - Logos: ${logos}
                - Scroll Distance: ${scrollDistance}px
                - Adjusted Speed: ${adjustedSpeed}px/s
                - Total Duration: ${totalDuration.toFixed(1)}s
                - Initial Delay: ${initialDelay}s`);

                // Hide loading indicator
                loadingIndicator.classList.remove('show');
                loadingIndicator.classList.add('fade-out');
                
                // Start the animation after a brief pause
                setTimeout(() => {
                    startCreditsAnimation(totalDuration, initialDelay);
                }, 800);

            }, 1500); // Loading indicator shown for 1.5 seconds
        }

        function startCreditsAnimation(duration, delay) {
            const scrollContent = document.getElementById('credits-content');
            
            if (!scrollContent || animationInProgress) return;
            
            animationInProgress = true;
            
            // Make content visible first (BLACKFILE appears)
            scrollContent.classList.add('visible');
            
            // Extended pause to let viewers read "BLACKFILE" and build anticipation
            setTimeout(() => {
                scrollContent.style.animation = `scroll-up ${duration}s linear forwards`;
            }, delay * 1000);
            
            // Reset animation state when complete
            setTimeout(() => {
                animationInProgress = false;
            }, (duration + delay) * 1000);
        }

        // Custom CSS animation via JavaScript
        const style = document.createElement('style');
        style.textContent = `
            @keyframes scroll-up {
                from { 
                    transform: translate(-50%, 0); 
                }
                to { 
                    transform: translate(-50%, -100%); 
                }
            }
        `;
        document.head.appendChild(style);

        // Auto-start when page loads
        window.addEventListener('load', () => {
            calculateOptimalTiming();
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (!animationInProgress) {
                // Recalculate if not currently animating
                setTimeout(calculateOptimalTiming, 500);
            }
        });
    </script>

</body>
</html>