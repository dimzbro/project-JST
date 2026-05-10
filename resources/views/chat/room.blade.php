<x-app-layout :hideSidebar="true">
    <style>
        /* Force page background */
        body, main.flex-1 {
            background-color: #ffffff !important;
        }
        main.flex-1 > div.p-4 {
            padding: 0 !important;
        }
        main.flex-1 > header {
            display: none !important; /* Hide default header, we use custom chat header */
        }
        
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%;
            background-color: #ffffff;
        }
        
        .chat-header {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid #eaeaea;
            background-color: #ffffff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .chat-bubble {
            max-width: 60%;
            padding: 12px 20px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        
        .bubble-right {
            align-self: flex-end;
            background-color: #c9eff9; /* Light blue from mockup */
            border-bottom-right-radius: 4px;
        }
        
        .bubble-left {
            align-self: flex-start;
            background-color: #e5e7eb; /* Light gray from mockup */
            border-bottom-left-radius: 4px;
        }
        
        .chat-input-area {
            padding: 16px 24px;
            background-color: #ffffff;
            border-top: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .chat-input-wrapper {
            flex: 1;
            background-color: #f3f4f6;
            border-radius: 24px;
            display: flex;
            align-items: center;
            padding: 8px 16px;
        }
        
        .chat-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-size: 14px;
            color: #333;
        }
        
        .chat-input::placeholder {
            color: #9ca3af;
        }
        
        .btn-send {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900 mr-4 transition-colors">
                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            
            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0 mr-3">
                @if($targetUser->profile_photo_path)
                    <img src="{{ Storage::url($targetUser->profile_photo_path) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($targetUser->first_name . ' ' . $targetUser->last_name) }}&color=7F9CF5&background=EBF4FF" class="w-full h-full object-cover">
                @endif
            </div>
            
            <h2 class="font-bold text-gray-900" style="font-size: 16px;">{{ $targetUser->first_name }} {{ $targetUser->last_name }}</h2>
        </div>
        
        <!-- Chat Body -->
        <div class="chat-body" id="chatBody">
            <!-- Messages will be injected here via Javascript -->
        </div>
        
        <!-- Chat Input -->
        <div class="chat-input-area">
            <div class="chat-input-wrapper">
                <input type="text" id="chatInput" class="chat-input focus:ring-0" placeholder="Ketik pesan disini...">
                <button type="button" id="btnSend" class="btn-send">
                    <svg style="width: 20px; height: 20px; transform: rotate(45deg); color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatInput = document.getElementById('chatInput');
            const btnSend = document.getElementById('btnSend');
            const chatBody = document.getElementById('chatBody');

            // Identify current user's role (client or worker)
            const currentRole = '{{ $isClient ? "client" : "worker" }}';

            // Unique key for this specific task's chat
            const sessionKey = 'chat_history_{{ $task->id }}';

            // 1. Load custom history from Session Storage
            let chatHistory = JSON.parse(sessionStorage.getItem(sessionKey)) || [];
            
            // Append saved history to the UI
            chatHistory.forEach(msg => {
                // Backward compatibility: if msg is just a string from older test, convert it
                if (typeof msg === 'string') {
                    msg = { sender: currentRole, text: msg };
                }
                
                // Safety check: skip if there's no text to prevent empty bubbles
                if (!msg || !msg.text || msg.text.trim() === '') return;

                const bubble = document.createElement('div');
                // If the message's sender matches my role, it's on the right. Otherwise, left.
                const isMine = msg.sender === currentRole;
                bubble.className = 'chat-bubble ' + (isMine ? 'bubble-right' : 'bubble-left');
                bubble.textContent = msg.text;
                chatBody.appendChild(bubble);
            });

            // Scroll to bottom naturally on load
            chatBody.scrollTop = chatBody.scrollHeight;

            function sendMessage() {
                const message = chatInput.value.trim();
                if (message === '') return;

                // Create new chat bubble for the active user
                const bubble = document.createElement('div');
                bubble.className = 'chat-bubble bubble-right';
                bubble.textContent = message;

                // Append to chat window
                chatBody.appendChild(bubble);

                // Save to session storage so it persists on reload across the browser
                chatHistory.push({ sender: currentRole, text: message });
                sessionStorage.setItem(sessionKey, JSON.stringify(chatHistory));

                // Clear input and auto-scroll to latest
                chatInput.value = '';
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            // Click send event
            btnSend.addEventListener('click', sendMessage);

            // Enter key event
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage();
                }
            });
        });
    </script>
</x-app-layout>
