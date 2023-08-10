<!DOCTYPE html>
<html>
<head>
  <title>Visualiseur PDF</title>
  <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
  <style>
    #pdfViewer canvas {
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>
  <input type="file" id="fileInput" accept=".pdf">
  <div id="pdfViewer"></div>
  <button id="prevPageBtn" disabled>&lt; Précédent</button>
  <button id="nextPageBtn" disabled>Suivant &gt;</button>
  <button id="submitBtn" disabled>Submit</button>

  <script>
    var pdfDoc = null;
    var currentPage = 1;
    var totalPages = 0;

    // Fonction pour charger et afficher le PDF
    function loadPDF(file) {
      var fileReader = new FileReader();

      fileReader.onload = function() {
        var typedArray = new Uint8Array(this.result);

        // Charger le PDF avec PDF.js
        pdfjsLib.getDocument(typedArray).promise.then(function(pdf) {
          pdfDoc = pdf;
          totalPages = pdf.numPages;

          // Afficher la première page du PDF
          showPage(currentPage);

          // Activer les boutons de navigation
          document.getElementById("prevPageBtn").disabled = false;
          document.getElementById("nextPageBtn").disabled = false;
          document.getElementById("submitBtn").disabled = false;
        });
      };

      fileReader.readAsArrayBuffer(file);
    }

    // Fonction pour afficher une page spécifique du PDF
    function showPage(pageNumber) {
      pdfDoc.getPage(pageNumber).then(function(page) {
        var pdfViewer = document.getElementById("pdfViewer");
        var canvas = document.createElement("canvas");
        var context = canvas.getContext("2d");
        var viewport = page.getViewport({ scale: 1.0 });

        canvas.width = viewport.width;
        canvas.height = viewport.height;

        page.render({
          canvasContext: context,
          viewport: viewport
        });

        // Vider le contenu précédent du visualiseur
        pdfViewer.innerHTML = "";
        pdfViewer.appendChild(canvas);

        // Mettre à jour le numéro de page actuel
        currentPage = pageNumber;

        // Désactiver le bouton Précédent s'il s'agit de la première page
        if (currentPage === 1) {
          document.getElementById("prevPageBtn").disabled = true;
        }

        // Désactiver le bouton Suivant s'il s'agit de la dernière page
        if (currentPage === totalPages) {
          document.getElementById("nextPageBtn").disabled = true;
        }
      });
    }

    // Événement de changement de fichier
    var fileInput = document.getElementById("fileInput");
    fileInput.addEventListener("change", function(e) {
      var file = e.target.files[0];
      loadPDF(file);
    });

    // Événement de clic sur le bouton Précédent
    var prevPageBtn = document.getElementById("prevPageBtn");
    prevPageBtn.addEventListener("click", function() {
      if (currentPage > 1) {
        showPage(currentPage - 1);
      }
    });

    // Événement de clic sur le bouton Suivant
    var nextPageBtn = document.getElementById("nextPageBtn");
    nextPageBtn.addEventListener("click", function() {
      if (currentPage < totalPages) {
        showPage(currentPage + 1);
      }
    });

    // Événement de clic sur le bouton Submit
    var submitBtn = document.getElementById("submitBtn");
    submitBtn.addEventListener("click", function() {
      // Ajoutez ici la logique pour soumettre le PDF
      alert("PDF soumis !");
    });
  </script>
</body>
</html>
