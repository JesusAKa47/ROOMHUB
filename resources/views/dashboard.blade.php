<x-app-layout>
    @push('styles')
    <style>
        /* Dashboard: paleta café — Primario #6F4E37, Secundario #A67C52, Acento #52796F, Fondo #FAF6F0 */
        .dash-page {
            background: linear-gradient(165deg, #FAF6F0 0%, #F5EDE4 40%, #EDE4D8 70%, #FAF6F0 100%);
            min-height: calc(100vh - 4rem);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem 3rem;
        }
        .dash-hero {
            text-align: center;
            margin-bottom: 2rem;
        }
        .dash-hero h1 {
            font-size: clamp(1.75rem, 5vw, 2.5rem);
            font-weight: 600;
            letter-spacing: 0.02em;
            line-height: 1.3;
            margin: 0;
            color: #1C1917;
        }
        .dash-hero h1 .neon {
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #6F4E37;
            text-shadow:
                0 0 10px rgba(166, 124, 82, 0.6),
                0 0 24px rgba(111, 78, 55, 0.4),
                0 0 48px rgba(111, 78, 55, 0.25);
            animation: neon-pulse 2.5s ease-in-out infinite;
        }
        @keyframes neon-pulse {
            0%, 100% { text-shadow: 0 0 10px rgba(166, 124, 82, 0.6), 0 0 24px rgba(111, 78, 55, 0.4), 0 0 48px rgba(111, 78, 55, 0.25); }
            50% { text-shadow: 0 0 14px rgba(166, 124, 82, 0.75), 0 0 32px rgba(111, 78, 55, 0.5), 0 0 56px rgba(111, 78, 55, 0.35); }
        }
        .dash-hero .hero-sub {
            font-size: clamp(0.85rem, 1.8vw, 1rem);
            color: #6B5344;
            margin-top: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
        .dash-webgl-wrap {
            position: relative;
            width: 100%;
            max-width: 960px;
            height: 65vh;
            min-height: 340px;
            max-height: 580px;
            margin: 0 auto;
            overflow: hidden;
            background: transparent;
            cursor: grab;
            border-radius: 1.25rem;
            box-shadow: 0 0 50px rgba(111, 78, 55, 0.1), 0 20px 60px rgba(0, 0, 0, 0.08);
        }
        .dash-webgl-wrap:active { cursor: grabbing; }
        .dash-webgl-wrap .webgl {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
            border-radius: inherit;
        }
    </style>
    @endpush

    <div class="dash-page">
        <div class="dash-hero">
            <h1>Bienvenidos a <span class="neon">RoomHub</span></h1>
            <p class="hero-sub">Tu espacio para rentar con seguridad</p>
        </div>
        <section class="dash-webgl-wrap" aria-label="Vista 3D">
            <canvas class="webgl"></canvas>
        </section>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r124/three.min.js"></script>
    <script src="https://unpkg.com/three@0.126.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://unpkg.com/three@0.126.0/examples/js/controls/OrbitControls.js"></script>
    <script>
    (function() {
        var container = document.querySelector('.dash-webgl-wrap');
        var canvas = document.querySelector('.dash-webgl-wrap .webgl');
        if (!container || !canvas) return;

        var scene = new THREE.Scene();
        var textureLoader = new THREE.TextureLoader();
        var sizes = { width: container.offsetWidth, height: container.offsetHeight };

        var camera = new THREE.PerspectiveCamera(10, sizes.width / sizes.height, 0.1, 100);
        camera.position.x = 18;
        camera.position.y = 8;
        camera.position.z = 20;
        scene.add(camera);

        var controls = new THREE.OrbitControls(camera, canvas);
        controls.enableDamping = true;
        controls.enableZoom = true;
        controls.enablePan = false;
        controls.minDistance = 20;
        controls.maxDistance = 40;
        controls.minPolarAngle = Math.PI / 4;
        controls.maxPolarAngle = Math.PI / 2;
        controls.minAzimuthAngle = -Math.PI / 80;
        controls.maxAzimuthAngle = Math.PI / 2.5;

        var renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
        renderer.setSize(sizes.width, sizes.height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.outputEncoding = THREE.sRGBEncoding;

        var bakedTexture = textureLoader.load('https://rawcdn.githack.com/ricardoolivaalonso/ThreeJS-Room05/ae27bdffd31dcc5cd5a919263f8f1c6874e05400/baked.jpg');
        bakedTexture.flipY = false;
        bakedTexture.encoding = THREE.sRGBEncoding;
        var bakedMaterial = new THREE.MeshBasicMaterial({ map: bakedTexture, side: THREE.DoubleSide });

        var loader = new THREE.GLTFLoader();
        loader.load(
            'https://rawcdn.githack.com/ricardoolivaalonso/ThreeJS-Room05/ae27bdffd31dcc5cd5a919263f8f1c6874e05400/model.glb',
            function(gltf) {
                var model = gltf.scene;
                model.traverse(function(child) { if (child.material) child.material = bakedMaterial; });
                scene.add(model);
            },
            function(xhr) { console.log((xhr.loaded / xhr.total * 100) + '% loaded'); }
        );

        window.addEventListener('resize', function() {
            sizes.width = container.offsetWidth;
            sizes.height = container.offsetHeight;
            camera.aspect = sizes.width / sizes.height;
            camera.updateProjectionMatrix();
            renderer.setSize(sizes.width, sizes.height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        });

        function tick() {
            controls.update();
            renderer.render(scene, camera);
            requestAnimationFrame(tick);
        }
        tick();
    })();
    </script>
    @endpush
</x-app-layout>
