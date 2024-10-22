@extends('layouts.app')

@section('title', 'Chat')

@section('content')
    <div class="d-flex justify-content-center flex-column align-items-center">
        <h1>{{ __('Chat Support') }}</h1>
        <div class="col-9 card">
            <div id="chat-window">
                <ul class="list-unstyled w-100 overflow-y-auto chat p-3" id="chat-messages" style="height: 400px"></ul>
                <div class="bg-white p-3">
                    <div data-mdb-input-init class="form-outline">
                        <textarea class="form-control bg-body-tertiary" id="message-input" rows="4" placeholder="{{ __('Message') }}"></textarea>
                    </div>
                </div>
                <button type="button" id="send-message" data-mdb-button-init data-mdb-ripple-init
                        class="btn btn-dark btn-rounded float-end me-3 mb-3">{{ __('Send') }}
                </button>
            </div>
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {

            let offset = 0;
            const limit = 20;
            let stop = false;
            const chatMessagesContainer = document.getElementById('chat-messages');
            const messageInput = document.getElementById('message-input');
            const sendMessageButton = document.getElementById('send-message');
            let isLoading = false;
            let chatId = {{$chats[0]->id}};

            window.Echo.private('chat.' + chatId)
                .listen('MessageSent', (e) => {
                    console.log(e);
                    addMessage(e.message, true, false);
                });

            loadMessages();

            chatMessagesContainer.addEventListener('scroll', () => {
                const scrollTop = chatMessagesContainer.scrollTop;

                if (scrollTop <= 30 && !isLoading) {
                    loadMoreMessages();
                }
            });

            function loadMessages(scrollToBottom = true) {
                isLoading = true;
                fetch(`/admin-panel/support/chat/${chatId}/messages?offset=${offset}`)
                    .then(response => response.json())
                    .then(messages => {
                        const oldScrollHeight = chatMessagesContainer.scrollHeight;
                        if (messages.length === 0) {
                            stop = true;
                        }
                        if (messages.length) {
                            messages.forEach(message => {
                                if (!chatMessagesContainer.querySelector(`[data-message-id="${message.id}"]`)) {
                                    addMessage(message, false, true);
                                }
                            });
                            offset += messages.length;
                        }

                        if (scrollToBottom) {
                            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                        } else {
                            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight - oldScrollHeight;
                        }
                        isLoading = false;
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        isLoading = false;
                    });
            }

            function loadMoreMessages() {
                if (!stop) {
                    loadMessages(false);
                }
            }

            function addMessage(message, scrollToBottom = true, prepend = false) {
                const userName = message.user ? message.user.name : 'Unknown User';
                const messageElement = document.createElement('div');
                messageElement.className = 'card mb-3';
                messageElement.setAttribute('data-message-id', message.id);
                messageElement.innerHTML = `
                    <div class="card-header d-flex justify-content-between p-3">
                        <div class="d-flex flex-row align-items-center">
                            <img class="img-fluid rounded-circle" style="height: 30px" src="${message.avatar_path}">
                            <p class="fw-bold ms-2 mb-0">${userName}</p>
                        </div>
                        <div>
                            <p class="text-muted small mb-0"><i class="far fa-clock"></i> ${new Date(message.created_at).toLocaleTimeString()}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">${message.text}</p>
                    </div>
                `;

                if (prepend) {
                    chatMessagesContainer.prepend(messageElement);
                } else {
                    chatMessagesContainer.append(messageElement);
                }

                if (scrollToBottom) {
                    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                }
            }

            sendMessageButton.addEventListener('click', () => {
                sendMessage();
            });

            function sendMessage() {
                const message = messageInput.value.trim();
                if (message === '') return;

                messageInput.value = '';

                fetch(`/chats/${chatId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ text: message, chat_id: chatId })
                })
                    .then(response => response.json())
            }
        });
    </script>

    <style>
        .chat::-webkit-scrollbar {
            width: 8px;
        }
        .chat::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .chat::-webkit-scrollbar-thumb {
            background-color: gray;
            border-radius: 20px;
            border: 1px solid #f8fafc;
        }
    </style>
@endsection
