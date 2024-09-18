<?php
function formatAIResponse($response)
{
    // Ubah ** menjadi tag <strong>
    $response = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $response);

    // Ubah baris yang dimulai dengan * menjadi list item
    $response = preg_replace('/^\* (.*?)$/m', '<li>$1</li>', $response);

    // Bungkus list items berurutan dengan tag <ul>
    $response = preg_replace('/(<li>.*?<\/li>(\s*)?)+/s', '<ul>$0</ul>', $response);

    return $response;
}
?>

<link rel="stylesheet" href="assets/css/bubblechat.css">

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
        <div class="chat-messages" id="chat-messages">
            <div id="loading-indicator" class="loading-indicator" style="display: none;">
                <div class="dot-flashing"></div>
            </div>
        </div>
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
        const loadingIndicator = document.getElementById('loading-indicator');

        let messageCounter = 0;
        const maxMessages = 5;
        let isResponding = false;
        let controller = null;

        function toggleChat() {
            if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
                chatContainer.style.display = 'flex';
                chatContainer.style.opacity = '0';
                setTimeout(() => {
                    chatContainer.style.opacity = '1';
                }, 50);
                chatToggle.style.display = 'none';
            } else {
                chatContainer.style.opacity = '0';
                setTimeout(() => {
                    chatContainer.style.display = 'none';
                    chatToggle.style.display = 'flex';
                }, 300);
            }
        }

        function showLoadingIndicator() {
            loadingIndicator.style.display = 'flex';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function hideLoadingIndicator() {
            loadingIndicator.style.display = 'none';
        }

        function addMessage(sender, content) {
            const messageElement = document.createElement('div');
            messageElement.className = `message message-${sender}`;

            if (sender === 'loading') {
                messageElement.innerHTML = `
                <div class="loading-indicator">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>`;
            } else if (sender === 'assistant') {
                messageElement.innerHTML = content;
            } else {
                messageElement.textContent = content;
            }

            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return messageElement; // Mengembalikan elemen untuk referensi nanti
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

        function adjustChatContainerSize() {
            if (window.innerWidth <= 480) {
                chatContainer.style.height = `${window.innerHeight - 140}px`;
            } else {
                chatContainer.style.height = '500px';
            }
        }

        chatToggle.addEventListener('click', toggleChat);
        chatClose.addEventListener('click', toggleChat);

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
                    const loadingMessage = addMessage('loading', '');
                    controller = new AbortController();
                    const response = await fetchAIResponse(message, controller.signal);
                    chatMessages.removeChild(loadingMessage); 
                    addMessage('assistant', response);
                } catch (error) {
                    console.error('Error:', error);
                    chatMessages.removeChild(loadingMessage); // Pastikan untuk menghapus loading jika terjadi error
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

        window.addEventListener('resize', adjustChatContainerSize);
        adjustChatContainerSize();
    });
</script>