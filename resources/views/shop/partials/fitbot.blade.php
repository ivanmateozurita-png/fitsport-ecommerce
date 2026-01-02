<div id="fitbot-container" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999;">
    <!-- Chat Button -->
    <button id="fitbot-toggle" style="width: 60px; height: 60px; border-radius: 50%; background: #000; color: #fff; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.3); font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
        <i class="fa fa-commenting-o"></i>
    </button>

    <!-- Chat Window -->
    <div id="fitbot-window" style="display: none; width: 350px; height: 500px; background: #fff; position: absolute; bottom: 80px; right: 0; box-shadow: 0 5px 20px rgba(0,0,0,0.15); border-radius: 12px; overflow: hidden; flex-direction: column;">
        
        <!-- Header -->
        <div style="background: #000; color: #fff; padding: 15px; display: flex; align-items: center;">
            <div style="width: 10px; height: 10px; background: #00ff00; border-radius: 50%; margin-right: 10px;"></div>
            <h5 style="margin: 0; color: #fff; font-size: 16px;">FitBot Assistant</h5>
            <button id="fitbot-close" style="background: none; border: none; color: #fff; margin-left: auto; cursor: pointer;">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="fitbot-messages" style="flex: 1; padding: 15px; overflow-y: auto; background: #f8f9fa;">
            <!-- Initial Message -->
            <div class="bot-message" style="background: #e9ecef; padding: 10px; border-radius: 10px 10px 10px 0; margin-bottom: 10px; max-width: 80%;">
                ¡Hola! Soy FitBot 🤖. ¿Qué estás buscando hoy? (Ej: "ropa para correr", "zapatillas", "accesorios")
            </div>
        </div>

        <!-- Input Area -->
        <div style="padding: 10px; background: #fff; border-top: 1px solid #dee2e6; display: flex;">
            <input type="text" id="fitbot-input" placeholder="Escribe tu consulta..." style="flex: 1; border: 1px solid #ced4da; border-radius: 20px; padding: 8px 15px; outline: none;">
            <button id="fitbot-send" style="background: #000; color: #fff; border: none; width: 40px; height: 35px; border-radius: 50%; margin-left: 10px; cursor: pointer;">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('fitbot-toggle');
    const closeBtn = document.getElementById('fitbot-close');
    const chatWindow = document.getElementById('fitbot-window');
    const input = document.getElementById('fitbot-input');
    const sendBtn = document.getElementById('fitbot-send');
    const messages = document.getElementById('fitbot-messages');

    // Toggle Chat
    function toggleChat() {
        if (chatWindow.style.display === 'none') {
            chatWindow.style.display = 'flex';
            toggleBtn.style.display = 'none';
        } else {
            chatWindow.style.display = 'none';
            toggleBtn.style.display = 'flex';
        }
    }
    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    // Send Message Logic
    async function sendMessage() {
        const text = input.value.trim();
        if (text === '') return;

        // Add User Message
        appendMessage(text, 'user');
        input.value = '';
        input.disabled = true;
        sendBtn.disabled = true;

        // Show Typing Indicator
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'bot-message';
        typingIndicator.id = 'typing-indicator';
        typingIndicator.style.cssText = 'background: #e9ecef; padding: 10px; border-radius: 10px 10px 10px 0; margin-bottom: 10px; max-width: 80%; font-style: italic; color: #6c757d;';
        typingIndicator.innerText = 'FitBot está escribiendo...';
        messages.appendChild(typingIndicator);
        messages.scrollTop = messages.scrollHeight;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }
            
            const response = await fetch('/fitbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            // Remove typing indicator
            document.getElementById('typing-indicator')?.remove();

            if (!response.ok) {
                const errorText = await response.text();
                console.error('FitBot Error:', response.status, errorText);
                appendMessage('Lo siento, el servicio no está disponible. Intenta más tarde.', 'bot');
                return;
            }

            const data = await response.json();
            appendMessage(data.response, 'bot');
            
        } catch (error) {
            console.error('FitBot Fetch Error:', error);
            document.getElementById('typing-indicator')?.remove();
            appendMessage('¡Hola! ¿En qué puedo ayudarte? Prueba preguntando por zapatillas o ropa.', 'bot');
        }

        input.disabled = false;
        sendBtn.disabled = false;
        input.focus();
    }

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    function appendMessage(text, sender) {
        const msgDiv = document.createElement('div');
        msgDiv.className = sender === 'user' ? 'user-message' : 'bot-message';
        
        const style = sender === 'user' 
            ? 'background: #000; color: #fff; padding: 10px; border-radius: 10px 10px 0 10px; margin-bottom: 10px; max-width: 80%; margin-left: auto;' 
            : 'background: #e9ecef; padding: 10px; border-radius: 10px 10px 10px 0; margin-bottom: 10px; max-width: 80%;';
            
        msgDiv.style.cssText = style;
        msgDiv.innerHTML = text;
        
        messages.appendChild(msgDiv);
        messages.scrollTop = messages.scrollHeight;
    }
});
</script>
