<!DOCTYPE html>
<html>
<head>
  <title>Visualiseur PDF</title>
  <style>
    #pdf-viewer {
      width: 100%;
      height: 500px;
    }
  </style>
</head>
<body>
  <input type="file" id="pdf-file" accept=".pdf">
  <div id="pdf-viewer"></div>

  <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
  <script>
    const fileInput = document.getElementById('pdf-file');
    const pdfViewer = document.getElementById('pdf-viewer');

    fileInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      const fileReader = new FileReader();

      fileReader.onload = function() {
        const typedArray = new Uint8Array(this.result);

        // Chargement du document PDF
        pdfjsLib.getDocument(typedArray).promise.then(function(pdf) {
          // Affichage de la première page du PDF
          pdf.getPage(1).then(function(page) {
            const scale = 1.5;
            const viewport = page.getViewport({ scale: scale });

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
              canvasContext: context,
              viewport: viewport
            };

            page.render(renderContext).promise.then(function() {
              pdfViewer.innerHTML = '';
              pdfViewer.appendChild(canvas);
            });
          });
        });
      };

      fileReader.readAsArrayBuffer(file);
    });
  </script>
</body>
</html>
