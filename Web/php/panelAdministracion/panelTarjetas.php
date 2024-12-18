<?php
if (!defined('ACCESO_PERMITIDO')) {
    // header('HTTP/1.0 403 Forbidden');
    // exit('No tienes permiso para acceder directamente a este archivo.');
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}
?>
<style>
    /* Estilo general de las tarjetas */
    .card {
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 20px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        color: #fff;
        overflow: hidden;
        position: relative;
        filter: grayscale(100%);
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
    }

    .active-card {
        filter: grayscale(0%);
        border: 3px solid #ff9900;
    }

    /* Estilo Revolut para la tarjeta de crédito */
    #tarjetaCredito {
        background: linear-gradient(135deg, #ff4c4c, #6a11cb);
        /* Degradado de rojo a morado similar al estilo Revolut */
        border: none;
    }

    /* Estilo original para la tarjeta de débito */
    #tarjetaDebito {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        /* Degradado morado original */
        border: none;
    }

    .card-body {
        padding: 2rem;
    }

    .card-title {
        font-weight: bold;
        font-size: 1.8rem;
        letter-spacing: 1px;
    }

    .card-text {
        font-size: 1.2rem;
    }

    .chip {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 50px;
        height: 35px;
        background: linear-gradient(135deg, #f3f4f5, #b8bec3);
        border-radius: 5px;
    }

    .logo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
    }

    .card-number {
        margin-top: 1.5rem;
        font-size: 1.2rem;
        letter-spacing: 3px;
    }

    .card-holder {
        margin-top: 1rem;
        font-size: 1rem;
        text-transform: uppercase;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.5rem;
        }

        .card-number {
            font-size: 1rem;
            letter-spacing: 2px;
        }

        .card-holder {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .card {
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .card-body {
            padding: 1rem;
        }

        .card-title {
            font-size: 1.3rem;
        }

        .card-number {
            font-size: 0.9rem;
            letter-spacing: 1.5px;
        }

        .card-holder {
            font-size: 0.8rem;
        }

        .chip {
            width: 40px;
            height: 28px;
        }

        .logo {
            width: 35px;
        }
    }
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
            <div id="tarjetaCredito" class="card position-relative">
                <div class="chip"></div>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1200px-Mastercard-logo.svg.png" alt="Mastercard Logo" class="logo">
                <div class="card-body text-center">
                    <h5 class="card-title">Tarjeta de Crédito</h5>
                    <div class="card-number" id="creditCardNumber">Cargando...</div>
                    <div class="card-holder">Nombre del Titular</div>
                    <div class="last-active" id="creditLastActive">Última actividad: Cargando...</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
            <div id="tarjetaDebito" class="card position-relative">
                <div class="chip"></div>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1200px-Mastercard-logo.svg.png" alt="Mastercard Logo" class="logo">
                <div class="card-body text-center">
                    <h5 class="card-title">Tarjeta de Débito</h5>
                    <div class="card-number" id="debitCardNumber">Cargando...</div>
                    <div class="card-holder">Nombre del Titular</div>
                    <div class="last-active" id="debitLastActive">Última actividad: Cargando...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    fetchCardInfo();

    async function fetchCardInfo() {
        const url = '../php/tarjetas/getCardInfo.php'; // Endpoint PHP para obtener los datos desencriptados

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Error al obtener la información de las tarjetas');
            }

            const cardInfo = await response.json();
            console.log('Datos desencriptados:', cardInfo);

            // Actualizar números de tarjeta
            document.getElementById('creditCardNumber').textContent = formatCardNumber(cardInfo.card_credit) || 'N/A';
            document.getElementById('debitCardNumber').textContent = formatCardNumber(cardInfo.card_debit) || 'N/A';

            // Actualizar última actividad
            document.getElementById('creditLastActive').textContent = `Última actividad: ${formatDate(cardInfo.lastactive_credit)}`;
            document.getElementById('debitLastActive').textContent = `Última actividad: ${formatDate(cardInfo.lastactive_debit)}`;

            // Activar automáticamente la tarjeta correspondiente
            if (cardInfo.current_card === 'CREDIT') {
                setActiveCard('credito');
            } else if (cardInfo.current_card === 'DEBIT') {
                setActiveCard('debito');
            }
        } catch (error) {
            console.error('Error durante el proceso de desencriptado:', error);
        }
    }

    // Agregar eventos de clic a las tarjetas
    document.getElementById('tarjetaCredito').addEventListener('click', () => handleCardClick('credito'));
    document.getElementById('tarjetaDebito').addEventListener('click', () => handleCardClick('debito'));

    async function handleCardClick(cardType) {
        setActiveCard(cardType);

        // Construye la URL según el tipo de tarjeta
        const url = `../php/proxyCard.php?cardType=${cardType === 'credito' ? 'CREDIT' : 'DEBIT'}`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Error al actualizar la tarjeta activa');
            }
            const result = await response.text();
            console.log('Respuesta del servidor:', result);
        } catch (error) {
            console.error('Error al realizar la solicitud:', error);
        }
    }

    function setActiveCard(cardType) {
        const tarjetaCredito = document.getElementById('tarjetaCredito');
        const tarjetaDebito = document.getElementById('tarjetaDebito');

        if (cardType === 'credito') {
            tarjetaCredito.classList.add('active-card');
            tarjetaDebito.classList.remove('active-card');
        } else if (cardType === 'debito') {
            tarjetaDebito.classList.add('active-card');
            tarjetaCredito.classList.remove('active-card');
        }
    }

    function formatCardNumber(number) {
        return number.replace(/\d(?=\d{4})/g, '•');
    }

    function formatDate(isoString) {
        const date = new Date(isoString);
        return date.toLocaleString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>