<!-- index.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Política de Privacidade</title>
    <style>
        .privacy-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .popup-content {
            background-color: white;
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container button {
            margin: 0 10px;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="privacyPopup" class="privacy-popup">
        <div class="popup-content">
            <h2>Política de Privacidade</h2>
            <div id="privacyContent">
                <!-- Aqui você irá inserir o conteúdo do PDF -->
                <p>Conteúdo da política de privacidade...</p>
            </div>
            <div class="button-container">
                <button onclick="acceptPrivacy()">Aceitar</button>
                <button onclick="rejectPrivacy()">Recusar</button>
            </div>
        </div>
    </div>

    <script>
        // Verifica se é a primeira visita
        function checkFirstVisit() {
            if (!localStorage.getItem('privacyAccepted')) {
                document.getElementById('privacyPopup').style.display = 'block';
            }
        }

        // Função para aceitar a política
        function acceptPrivacy() {
            localStorage.setItem('privacyAccepted', 'true');
            document.getElementById('privacyPopup').style.display = 'none';
        }

        // Função para recusar a política
        function rejectPrivacy() {
            window.close();
            // Caso window.close() não funcione (alguns navegadores bloqueiam)
            document.body.innerHTML = '<h1>Acesso negado</h1>';
        }

        // Executa a verificação quando a página carrega
        window.onload = checkFirstVisit;
    </script>
</body>
</html>