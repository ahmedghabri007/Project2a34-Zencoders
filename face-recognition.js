const video = document.getElementById('video');

// Load models from 'models/' folder
Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('models'),
  faceapi.nets.faceExpressionNet.loadFromUri('models'),
  faceapi.nets.ageGenderNet.loadFromUri('models'),
  faceapi.nets.ssdMobilenetv1.loadFromUri('models') // Optional: for better accuracy
]).then(startVideo).catch(console.error);

// Start webcam
function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: {} })
    .then(stream => video.srcObject = stream)
    .catch(err => console.error("Webcam error:", err));
}

// Run detection once video starts playing
video.addEventListener('play', () => {
  const canvas = faceapi.createCanvasFromMedia(video);
  document.body.append(canvas);

  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(canvas, displaySize);

  setInterval(async () => {
    const detections = await faceapi
      .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()) // Use SSD if preferred
      .withFaceLandmarks()
      .withFaceExpressions()
      .withAgeAndGender()
      .withFaceDescriptors();

    const resized = faceapi.resizeResults(detections, displaySize);

    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
    faceapi.draw.drawDetections(canvas, resized);
    faceapi.draw.drawFaceLandmarks(canvas, resized);
    faceapi.draw.drawFaceExpressions(canvas, resized);

    resized.forEach(result => {
      const { age, gender, genderProbability } = result;
      const box = result.detection.box;
      const label = `${Math.round(age)} yrs - ${gender} (${(genderProbability * 100).toFixed(1)}%)`;
      const drawBox = new faceapi.draw.DrawBox(box, { label });
      drawBox.draw(canvas);
    });
  }, 300);
});
