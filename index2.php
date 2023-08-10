<!DOCTYPE html>
<html>
<head>
  <title>Visualiseur PDF interactif</title>
  <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
  <style>
    #pdfViewer {
      width: 100%;
      height: 500px;
    }
  </style>
</head>
<body>
  <form id="pdfForm" action="traitement.php" method="POST">
    <input type="file" id="fileInput" accept=".pdf">
    <div id="pdfViewer"></div>
    <button type="submit">Soumettre</button>
  </form>

  <script>
    // Fonction pour charger et afficher le PDF
    function loadPDF(file) {
      var fileReader = new FileReader();

      fileReader.onload = function() {
        var typedArray = new Uint8Array(this.result);

        // Charger le PDF avec PDF.js
        pdfjsLib.getDocument(typedArray).promise.then(function(pdf) {
          // Afficher la première page du PDF
          pdf.getPage(1).then(function(page) {
            var canvas = document.createElement("canvas");
            var context = canvas.getContext("2d");
            var viewport = page.getViewport({ scale: 1.0 });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            page.render({
              canvasContext: context,
              viewport: viewport
            }).promise.then(function() {
              // Ajouter le canvas avec la page rendue dans le div du visualiseur
              var pdfViewer = document.getElementById("pdfViewer");
              pdfViewer.appendChild(canvas);
            });
          });
        });
      };

      fileReader.readAsArrayBuffer(file);
    }

    // Événement de changement de fichier
    var fileInput = document.getElementById("fileInput");
    fileInput.addEventListener("change", function(e) {
      var file = e.target.files[0];
      loadPDF(file);
    });

    // Événement de soumission du formulaire
    var pdfForm = document.getElementById("pdfForm");
    pdfForm.addEventListener("submit", function(e) {
      e.preventDefault();
      // Effectuer le traitement du formulaire ici
      // par exemple, récupérer les données du visualiseur PDF avant de soumettre le formulaire
      var pdfViewer = document.getElementById("pdfViewer");
      var canvas = pdfViewer.querySelector("canvas");
      var imageData = canvas.toDataURL("image/jpeg");
      // Ajouter les données d'image au formulaire pour les envoyer
      var hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.name = "pdfImageData";
      hiddenInput.value = imageData;
      pdfForm.appendChild(hiddenInput);
      // Soumettre le formulaire
      pdfForm.submit();
    });
  </script>
</body>
</html>
