<?php
// components/bubblechat.php
?>
<style>
    .chat-widget {
        position: fixed;
        bottom: 20px;
        left: 20px;
        /* Ubah ke sisi kiri */
        z-index: 1000;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .chat-toggle {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s, transform 0.3s;
    }

    .chat-toggle:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    .chat-container {
        display: none;
        position: fixed;
        /* Ubah ke fixed untuk tampilan mobile */
        bottom: 80px;
        left: 20px;
        /* Sesuaikan dengan posisi tombol */
        width: 350px;
        height: 500px;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .chat-header {
        background-color: #007bff;
        color: white;
        padding: 15px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-privacy-notice {
        background-color: #fff3cd;
        color: #856404;
        padding: 10px;
        font-size: 14px;
        text-align: center;
        border-bottom: 1px solid #ffeeba;
    }

    .chat-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .chat-close:hover {
        transform: scale(1.1);
    }

    .chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        background-color: #f8f9fa;
    }

    .chat-input-container {
        padding: 15px;
        background-color: white;
        border-top: 1px solid #e0e0e0;
    }

    .chat-input-form {
        display: flex;
        gap: 10px;
    }

    .chat-input {
        flex-grow: 1;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 20px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .chat-input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .chat-submit {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .chat-submit:hover {
        background-color: #0056b3;
    }

    .message {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 18px;
        margin-bottom: 5px;
        font-size: 14px;
        line-height: 1.4;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .message-user {
        align-self: flex-end;
        background-color: #007bff;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-assistant {
        align-self: flex-start;
        background-color: #e9ecef;
        color: #212529;
        border-bottom-left-radius: 4px;
    }

    .message-system {
        align-self: center;
        background-color: #ffc107;
        color: #212529;
        border-radius: 12px;
        font-style: italic;
    }

    .chat-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #6c757d;
        margin-top: 10px;
        padding: 0 5px;
    }

    .stop-respond {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 12px;
        padding: 0;
        transition: color 0.2s;
    }

    .stop-respond:hover {
        color: #a71d2a;
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .chat-widget {
            bottom: 70px;
            /* Sesuaikan agar tidak menutupi elemen lain */
            left: 10px;
        }

        .chat-container {
            width: calc(100% - 20px);
            /* Kurangi lebar untuk margin */
            height: calc(100% - 140px);
            /* Sesuaikan tinggi */
            bottom: 70px;
            left: 10px;
            right: 10px;
            border-radius: 10px;
        }

        .chat-toggle {
            width: 50px;
            height: 50px;
        }
    }
</style>

<div class="chat-widget" id="chat-widget">
    <button class="chat-toggle" id="chat-toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>

    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <span>Asisten SMKN 1 Bolaang</span>
            <button class="chat-close" id="chat-close">&times;</button>
        </div>
        <div class="chat-privacy-notice">
            Perhatian: Jangan memasukkan data pribadi atau informasi sensitif dalam chat ini.
        </div>
        <div class="chat-messages" id="chat-messages"></div>
        <div class="chat-input-container">
            <form class="chat-input-form" id="chat-form">
                <input type="text" class="chat-input" id="user-input" placeholder="Ketik pesan Anda..." required>
                <button type="submit" class="chat-submit">Kirim</button>
            </form>
            <div class="chat-info">
                <span id="message-count">0/5 pesan</span>
                <button type="button" id="stop-respond" class="stop-respond" style="display:none;">Hentikan Respon</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatWidget = document.getElementById('chat-widget');
        const chatToggle = document.getElementById('chat-toggle');
        const chatContainer = document.getElementById('chat-container');
        const chatClose = document.getElementById('chat-close');
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const messageCount = document.getElementById('message-count');
        const stopRespond = document.getElementById('stop-respond');

        let messageCounter = 0;
        const maxMessages = 5;
        let isResponding = false;
        let controller = null;

        chatToggle.addEventListener('click', toggleChat);
        chatClose.addEventListener('click', toggleChat);

        function toggleChat() {
            if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
                chatContainer.style.display = 'flex';
                chatContainer.style.opacity = '0';
                setTimeout(() => {
                    chatContainer.style.opacity = '1';
                }, 50);
                chatToggle.style.display = 'none'; // Sembunyikan tombol toggle saat chat terbuka
            } else {
                chatContainer.style.opacity = '0';
                setTimeout(() => {
                    chatContainer.style.display = 'none';
                    chatToggle.style.display = 'flex'; // Tampilkan kembali tombol toggle saat chat tertutup
                }, 300);
            }
        }


        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = userInput.value.trim();
            if (message && messageCounter < maxMessages) {
                addMessage('user', message);
                userInput.value = '';
                messageCounter++;
                updateMessageCount();

                if (messageCounter >= maxMessages) {
                    addMessage('system', 'Batas maksimum percakapan tercapai. Silakan mulai percakapan baru.');
                    return;
                }

                try {
                    isResponding = true;
                    stopRespond.style.display = 'inline';
                    controller = new AbortController();
                    const response = await fetchAIResponse(message, controller.signal);
                    addMessage('assistant', response);
                } catch (error) {
                    console.error('Error:', error);
                    if (error.name === 'AbortError') {
                        addMessage('system', 'Respons dihentikan.');
                    } else {
                        addMessage('system', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.');
                    }
                } finally {
                    isResponding = false;
                    stopRespond.style.display = 'none';
                    controller = null;
                }
            }
        });

        stopRespond.addEventListener('click', () => {
            if (isResponding && controller) {
                controller.abort();
                isResponding = false;
                stopRespond.style.display = 'none';
            }
        });

        function addMessage(sender, content) {
            const messageElement = document.createElement('div');
            messageElement.className = `message message-${sender}`;
            messageElement.textContent = content;
            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function updateMessageCount() {
            messageCount.textContent = `${messageCounter}/${maxMessages} pesan`;
        }

        async function fetchAIResponse(message, signal) {
            try {
                const response = await fetch('api/process_chat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `message=${encodeURIComponent(message)}`,
                    signal: signal
                });
                if (!response.ok) {
                    if (response.status === 429) {
                        throw new Error('Batas penggunaan API tercapai. Silakan coba lagi nanti.');
                    }
                    throw new Error('Network response was not ok');
                }
                const data = await response.text();
                return data;
            } catch (error) {
                console.error('Error:', error);
                throw error;
            }
        }
        window.addEventListener('resize', adjustChatContainerSize);

        function adjustChatContainerSize() {
            if (window.innerWidth <= 480) {
                chatContainer.style.height = `${window.innerHeight - 140}px`;
            } else {
                chatContainer.style.height = '500px';
            }
        }

        // Panggil fungsi ini saat halaman dimuat
        adjustChatContainerSize();
    });
</script>