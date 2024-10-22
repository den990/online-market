@extends('admin.layouts.main')

@section('title', 'Support')

@section('admin-content')
    <section>
        <div class="container ">
            <div class="row">
                <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">
                    <h5 class="font-weight-bold mb-3 text-center text-lg-start">Member</h5>
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-unstyled mb-0" id="chat-list">
                                @foreach($chats as $chat)
                                    <li class="p-2 border-bottom bg-body-tertiary">
                                        <a href="#" class="d-flex justify-content-between chat-link" data-chat-id="{{ $chat->id }}">
                                            <div class="d-flex flex-row">
                                                <img src="{{ $chat->user1->isAdmin() ? $chat->user2->avatar_path : $chat->user1->avatar_path }}" alt="avatar"
                                                     class="rounded-circle d-flex align-self-center me-3 shadow-1-strong" width="60">
                                                <div class="pt-1">
                                                    <p class="fw-bold mb-0">{{ $chat->user1->isAdmin() ? $chat->user2->name : $chat->user1->name }}</p>
                                                    <p class="small text-muted">Click to view messages</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-7 col-xl-8">
                    <div id="chat-window" style="display: none;" >
                        <ul class="list-unstyled w-100 overflow-y-auto chat" id="chat-messages" style="height: 400px"></ul>
                        <div class="bg-white mb-3">
                            <div data-mdb-input-init class="form-outline">
                                <textarea class="form-control bg-body-tertiary" id="message-input" rows="4"></textarea>
                                <label class="form-label" for="message-input">Message</label>
                            </div>
                        </div>
                        <button type="button" id="send-message" data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-rounded float-end">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            event.preventDefault();
            var offset = 0;
            let currentChatId = null;
            const limit = 20;
            var stop = false;
            const chatMessagesContainer = document.getElementById('chat-messages');
            const chatWindow = document.getElementById('chat-window');
            const messageInput = document.getElementById('message-input');
            const sendMessageButton = document.getElementById('send-message');
            let isLoading = false;



            document.querySelectorAll('.chat-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const chatId = this.dataset.chatId;
                    loadChat(chatId);

                    window.Echo.private('chat.' + chatId)
                        .listen('MessageSent', (e) => {
                            addMessage(e.message, true, false);
                        });
                });
            });

            chatMessagesContainer.addEventListener('scroll', () => {
                const scrollTop = chatMessagesContainer.scrollTop;
                const clientHeight = chatMessagesContainer.clientHeight;
                const scrollHeight = chatMessagesContainer.scrollHeight;


                if (scrollTop <= 30 && !isLoading) {
                    loadMoreMessages();
                }
            });

            function loadChat(chatId) {
                currentChatId = chatId;
                offset = 0;
                stop = false;
                chatMessagesContainer.innerHTML = '';
                chatWindow.style.display = 'block';
                fetchMessages(chatId, true);
            }

            function fetchMessages(chatId, scrollToBottom = false) {
                isLoading = true;
                if (!stop) {
                    fetch(`/admin-panel/support/chat/${chatId}/messages?offset=${offset}`)
                        .then(response => response.json())
                        .then(messages => {
                            const oldScrollHeight = chatMessagesContainer.scrollHeight;
                            if (messages.length == 0) {
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
            }

            function loadMoreMessages() {
                if (currentChatId) {
                    fetchMessages(currentChatId, false);
                }
            }

            function addMessage(message, scrollToBottom = true, prepend) {
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
                if (prepend)
                    chatMessagesContainer.prepend(messageElement);
                else
                    chatMessagesContainer.append(messageElement);

                if (scrollToBottom) {
                    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                }
            }

            sendMessageButton.addEventListener('click', () => {
                if (currentChatId) {
                    sendMessage(currentChatId);
                }
            });

            function sendMessage(chatId) {
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
