<div class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="w-full max-w-4xl p-4 mx-auto">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

      <!-- Webcam Capture -->
      <div class="space-y-4 text-center">
        <h2 class="text-lg font-semibold text-gray-700">Capture live photo</h2>
        <div id="my_camera" class="w-full h-[287px] bg-black rounded-md overflow-hidden"></div>
        <input type="hidden" name="captured_image_data" id="captured_image_data">
        <button
          class="px-4 py-2 mt-2 text-white transition bg-blue-500 rounded-md hover:bg-blue-600"
          onclick="take_snapshot()">
          Take Snapshot
        </button>
      </div>

      <!-- Snapshot Result -->
      <div class="space-y-4 text-center">
        <h2 class="text-lg font-semibold text-gray-700">Result</h2>
        <div id="results">
          <img class="w-[350px] mx-auto rounded-md" src="image_placeholder.jpg" />
        </div>
        <button
          class="px-4 py-2 text-white transition bg-green-500 rounded-md hover:bg-green-600"
          onclick="saveSnap()">
          Save Picture
        </button>
      </div>

    </div>
  </div>
</div>

<script>
  // Webcam config
  Webcam.set({
    width: 350,
    height: 287,
    image_format: 'jpeg',
    jpeg_quality: 90
  });
  Webcam.attach('#my_camera');

  function take_snapshot() {
    Webcam.snap(function(data_uri) {
      document.getElementById('results').innerHTML =
        '<img class="w-[350px] mx-auto rounded-md" src="' + data_uri + '"/>';
      document.getElementById("captured_image_data").value = data_uri;
    });
  }

  function saveSnap() {
    const base64data = document.getElementById("captured_image_data").value;

    fetch("http://127.0.0.1:8000/ocr", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          image: base64data
        })
      })
      .then(res => res.json())
      .then(data => {
        alert("Hasil OCR: " + JSON.stringify(data));
      })
      .catch(err => {
        console.error(err);
        alert("Upload failed");
      });
  }
</script>