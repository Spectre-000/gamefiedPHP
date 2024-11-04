<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Gamefied Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js" integrity="sha512-U5C477Z8VvmbYAoV4HDq17tf4wG6HXPC6/KM9+0/wEXQQ13gmKY2Zb0Z2vu0VNUWch4GlJ+Tl/dfoLOH4i2msw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        body {
            background-color: #ffe4ce;
        }
        .modulePage {
            padding: 10px;
            max-width: 100%;
            overflow: hidden;
        }
        #pdf-viewer {
            width: 100%;
            height: calc(100vh - 100px);
            overflow: hidden;
            border: 1px solid #ccc;
            position: relative;
        }
        #pdf-canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
        .pdf-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .pdf-controls button {
            margin: 5px;
            padding: 10px 15px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            cursor: pointer;
            font-size: 16px;
            touch-action: manipulation;
        }
        .pdf-controls button:hover {
            background-color: #e0e0e0;
        }
        #page_num, #page_count {
            font-weight: bold;
        }
        #zoom-controls {
            display: flex;
            align-items: center;
        }
        #zoom-controls button {
            font-size: 18px;
            padding: 5px 10px;
        }
        #current-zoom {
            margin: 0 10px;
        }
        @media (max-width: 768px) {
            .pdf-controls {
                flex-direction: column;
                align-items: stretch;
            }
            .pdf-controls > div {
                display: flex;
                justify-content: center;
                margin: 5px 0;
            }
            #zoom-controls {
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <div class="modulePage">
        <div class="pdf-controls">
            <div>
                <button id="prev">Previous</button>
                <button id="next">Next</button>
                <button id="return-home" onclick="window.location.href='adminModule.php'">Return to main page</button>
            </div>
            <div>Page: <span id="page_num"></span> / <span id="page_count"></span></div>
            <div id="zoom-controls">
                <button id="zoom-out">-</button>
                <span id="current-zoom">100%</span>
                <button id="zoom-in">+</button>
            </div>
        </div>
        <div id="pdf-viewer">
            <canvas id="pdf-canvas"></canvas>
        </div>
    </div>

    <script>
        if (typeof pdfjsLib === 'undefined') {
            console.error('PDF.js library not loaded!');
        } else {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';
        }

        var pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.0,
            canvas = document.getElementById('pdf-canvas'),
            ctx = canvas.getContext('2d'),
            viewport,
            pdfViewer = document.getElementById('pdf-viewer');

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                var viewerWidth = pdfViewer.clientWidth;
                viewport = page.getViewport({scale: 1});
                scale = viewerWidth / viewport.width;
                viewport = page.getViewport({scale: scale});

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            document.getElementById('page_num').textContent = num;
            updateZoomDisplay();
        }
        if (pdfUrl) {
            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
                pdfDoc = pdfDoc_;
                document.getElementById('page_count').textContent = pdfDoc.numPages;
                renderPage(pageNum);
            }).catch(function(error) { // Ensure 'error' is defined here
                console.error('Error loading PDF:', error.message);
                document.getElementById('pdf-viewer').textContent = 'Error loading PDF: ' + error.message;
            });
        } else {
            document.getElementById('pdf-viewer').textContent = 'No PDF file specified.';
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function onPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        function onZoomIn() {
            if (scale < 3) {
                scale += 0.25;
                queueRenderPage(pageNum);
            }
        }

        function onZoomOut() {
            if (scale > 0.5) {
                scale -= 0.25;
                queueRenderPage(pageNum);
            }
        }

        function updateZoomDisplay() {
            document.getElementById('current-zoom').textContent = Math.round(scale * 100) + '%';
        }

        // Touch-based panning
        let isDragging = false;
        let startX, startY, startLeft, startTop;

        canvas.addEventListener('touchstart', function(e) {
            isDragging = true;
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            startLeft = canvas.offsetLeft;
            startTop = canvas.offsetTop;
        });

        canvas.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            e.preventDefault();
            let touchX = e.touches[0].clientX;
            let touchY = e.touches[0].clientY;
            let dx = touchX - startX;
            let dy = touchY - startY;
            canvas.style.left = startLeft + dx + 'px';
            canvas.style.top = startTop + dy + 'px';
        });

        canvas.addEventListener('touchend', function() {
            isDragging = false;
        });

        // Pinch-to-zoom
        let initialPinchDistance = 0;
        let initialScale = 1;

        canvas.addEventListener('touchstart', function(e) {
            if (e.touches.length === 2) {
                initialPinchDistance = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                initialScale = scale;
            }
        });

        canvas.addEventListener('touchmove', function(e) {
            if (e.touches.length === 2) {
                e.preventDefault();
                let currentDistance = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                let scaleDiff = currentDistance / initialPinchDistance;
                scale = initialScale * scaleDiff;
                scale = Math.min(Math.max(scale, 0.5), 3);
                queueRenderPage(pageNum);
            }
        });

        document.getElementById('prev').addEventListener('click', onPrevPage);
        document.getElementById('next').addEventListener('click', onNextPage);
        document.getElementById('zoom-in').addEventListener('click', onZoomIn);
        document.getElementById('zoom-out').addEventListener('click', onZoomOut);

        <?php
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="filename.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        if (isset($_GET["loc"])) {
            $loc = htmlspecialchars($_GET['loc']);
            echo "var pdfUrl = 'modules/{$loc}'; console.log('PDF URL: ' + pdfUrl);";
        } else {
            echo "var pdfUrl = ''; console.log('No PDF file specified');";
        }
        ?>

        if (pdfUrl) {
            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
                pdfDoc = pdfDoc_;
                document.getElementById('page_count').textContent = pdfDoc.numPages;
                renderPage(pageNum);
            }).catch(function(error) {
                console.error('Error loading PDF:', error);
                document.getElementById('pdf-viewer').textContent = 'Error loading PDF: ' + error.message;
            });
        } else {
            document.getElementById('pdf-viewer').textContent = 'No PDF file specified.';
        }
        console.error('Error loading PDF:', error.message, error);
    </script>
</body>
</html>