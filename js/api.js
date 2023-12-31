document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.querySelector('main');
    const fileInput = document.querySelector('input[type="file"]');
    const canvas = document.querySelector('canvas');
    const context = canvas.getContext('2d');
    const colorForm = document.querySelector('input[type="color"]');

    let isDrawing = false;
    context.lineJoin = 'round';
    context.lineCap = 'round';
    context.lineWidth = 5;
    context.strokeStyle = colorForm.value;

    dropArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropArea.style.backgroundColor = '#f0f0f0';
    });

    dropArea.addEventListener('dragleave', function () {
        dropArea.style.backgroundColor = '';
    });

    dropArea.addEventListener('drop', function (e) {
        e.preventDefault();
        dropArea.style.backgroundColor = '';
        handleFileSelect(e);
    });

    fileInput.addEventListener('change', handleFileSelect);

    colorForm.addEventListener('input', function () {
        context.strokeStyle = colorForm.value;
    });

    canvas.addEventListener('mousedown', function (e) {
        isDrawing = true;
        draw(e);
    });

    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', function () {
        isDrawing = false;
    });
    canvas.addEventListener('mouseout', function () {
        isDrawing = false;
    });

    function draw(e) {
        if (!isDrawing) return;

        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        context.beginPath();
        context.moveTo(x, y);
        context.lineTo(x, y);
        context.stroke();
    }

    function handleFileSelect(e) {
        const file = (e.type === 'drop') ? e.dataTransfer.files[0] : e.target.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = new Image();

                img.onload = function () {
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    context.drawImage(img, 0, 0, canvas.width, canvas.height);
                };

                img.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }
    }
});