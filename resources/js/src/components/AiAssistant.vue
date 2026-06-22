<template>
  <div class="luxury-ai-assistant">
    
    <button
      v-if="!isOpen"
      class="chat-trigger-btn"
      @click="isOpen = true"
      aria-label="Открыть чат с ассистентом"
    >
      <div class="trigger-icon-wrapper">
        <i class="bi bi-chat-left-dots-fill"></i>
        <span class="trigger-pulse"></span>
      </div>
    </button>

    <div
      v-if="isOpen"
      class="assistant-window animate__animated animate__fadeInUp"
    >
      <div class="assistant-header">
        <div class="header-left">
          <button class="mobile-back-btn" @click="isOpen = false" title="Назад">
            <i class="bi bi-arrow-left"></i>
          </button>

          <div class="avatar-wrapper">
            <div class="avatar-core">
              <i class="bi bi-cpu"></i>
            </div>
            <span class="online-indicator"></span>
          </div>

          <div>
            <h4 class="assistant-title">CARMAX AI</h4>
            <span class="assistant-subtitle">Интеллектуальный помощник</span>
          </div>
        </div>

        <button
          class="close-widget-btn"
          @click="isOpen = false"
          title="Свернуть чат"
        >
          <i class="bi bi-xlg"></i>
        </button>
      </div>

      <div
        class="chat-body"
        ref="chatBody"
      >
        <div
          v-for="(msg, index) in messages"
          :key="index"
          class="message-wrapper"
          :class="msg.role === 'user' ? 'wrapper-user' : 'wrapper-bot'"
        >
          <div class="message-bubble" :class="msg.role">
            {{ msg.text }}
          </div>
        </div>

        <div
          v-if="loading"
          class="message-wrapper wrapper-bot animate__animated animate__fadeIn"
        >
          <div class="message-bubble bot typing-indicator">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
          </div>
        </div>
      </div>

      <div class="quick-actions-container">
        <button class="action-chip" @click="quickQuestion('Какие машины доступны?')">
          <i class="bi bi-car-front me-1"></i> Доступные авто
        </button>

        <button class="action-chip" @click="quickQuestion('Сколько стоит аренда?')">
          <i class="bi bi-cash-stack me-1"></i> Стоимость
        </button>

        <button class="action-chip" @click="quickQuestion('Какие документы нужны?')">
          <i class="bi bi-file-earmark-text me-1"></i> Документы
        </button>

        <button class="action-chip" @click="quickQuestion('Как забронировать машину?')">
          <i class="bi bi-calendar-check me-1"></i> Бронирование
        </button>
      </div>

      <div class="close-action-wrapper">
        <button class="close-text-btn" @click="isOpen = false">
          <i class="bi bi-chevron-down"></i> Свернуть окно ассистента
        </button>
      </div>

      <div class="chat-footer">
        <div class="input-group-custom">
          <input
            v-model="userMessage"
            @keyup.enter="sendMessage"
            type="text"
            placeholder="Задайте ваш вопрос..."
            ref="messageInput"
          >
          <button 
            @click="sendMessage" 
            class="send-message-btn"
            :class="{'btn-active': userMessage.trim().length > 0}"
            title="Отправить сообщение"
          >
            <i class="bi bi-send-fill"></i>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { askGemini } from "../services/gemini";
import { ref, nextTick } from "vue";

const isOpen = ref(false);
const userMessage = ref("");
const loading = ref(false);
const chatBody = ref(null);

const messages = ref([
  {
    role: "bot",
    text: "Здравствуйте! Я Ваш персональный AI-консультант CARMAX. Чем могу помочь?"
  }
]);

function scrollBottom() {
  nextTick(() => {
    if (chatBody.value) {
      chatBody.value.scrollTo({
        top: chatBody.value.scrollHeight,
        behavior: "smooth"
      });
    }
  });
}

function quickQuestion(question) {
  userMessage.value = question;
  sendMessage();
}

async function sendMessage() {
  if (!userMessage.value.trim()) return;

  const text = userMessage.value;

  messages.value.push({
    role: "user",
    text
  });

  userMessage.value = "";
  loading.value = true;

  scrollBottom();

  try {
    const answer = await askGemini(text);

    messages.value.push({
      role: "bot",
      text: answer
    });
  } catch (error) {
    console.error(error);

    messages.value.push({
      role: "bot",
      text: "Извините, сейчас не удалось получить ответ от AI."
    });
  }

  loading.value = false;
  scrollBottom();
}
</script>

<style scoped>
/* ПЛАВАЮЩАЯ КНОПКА ВЫЗОВА */
.chat-trigger-btn {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 64px;
  height: 64px;
  border-radius: 20px;
  border: none;
  background: #111827;
  color: #ffffff;
  cursor: pointer;
  z-index: 9999;
  box-shadow: 0 12px 30px rgba(17, 24, 39, 0.25);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  justify-content: center;
}

.chat-trigger-btn:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 16px 35px rgba(17, 24, 39, 0.35);
  background: #1f2937;
}

.trigger-icon-wrapper {
  position: relative;
  font-size: 1.5rem;
  display: flex;
}

.trigger-pulse {
  position: absolute;
  top: -4px;
  right: -4px;
  width: 10px;
  height: 10px;
  background-color: #22c55e;
  border-radius: 50%;
  border: 2px solid #111827;
}

/* ОКНО ВИДЖЕТА ЧАТА */
.assistant-window {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 410px;
  height: 680px;
  background: #ffffff;
  border-radius: 24px;
  overflow: hidden;
  z-index: 9999;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
  border: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
}

/* ВЕРХНЯЯ ПАНЕЛЬ ЧАТА (HEADER) */
.assistant-header {
  background: #ffffff;
  border-bottom: 1px solid #f3f4f6;
  padding: 16px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 14px;
}

.mobile-back-btn {
  display: none; /* Скрыта на десктопе */
  border: none;
  background: transparent;
  color: #111827;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 4px;
  transition: opacity 0.2s;
}

.mobile-back-btn:hover {
  opacity: 0.7;
}

.avatar-wrapper {
  position: relative;
}

.avatar-core {
  width: 44px;
  height: 44px;
  border-radius: 14px;
  background: #f3f4f6;
  color: #111827;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.3rem;
  border: 1px solid #e5e7eb;
}

.online-indicator {
  position: absolute;
  bottom: -2px;
  right: -2px;
  width: 11px;
  height: 11px;
  background: #22c55e;
  border: 2px solid #ffffff;
  border-radius: 50%;
}

.assistant-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #111827;
  margin: 0;
  letter-spacing: 0.3px;
}

.assistant-subtitle {
  font-size: 0.78rem;
  color: #6b7280;
  display: block;
}

.close-widget-btn {
  position: relative;
  z-index: 10;
  border: none;
  background: transparent;
  color: #9ca3af;
  font-size: 1rem;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-widget-btn:hover {
  background: #f3f4f6;
  color: #111827;
}

/* ДИАЛОГОВАЯ ЗОНА (BODY) */
.chat-body {
  flex: 1;
  padding: 24px 20px;
  overflow-y: auto;
  background: #f9fafb;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.message-wrapper {
  display: flex;
  width: 100%;
}

.wrapper-bot {
  justify-content: flex-start;
}

.wrapper-user {
  justify-content: flex-end;
}

.message-bubble {
  padding: 12px 16px;
  font-size: 0.9rem;
  line-height: 1.5;
  max-width: 82%;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
}

.bot {
  background: #ffffff;
  color: #374151;
  border-radius: 4px 16px 16px 16px;
  border: 1px solid #e5e7eb;
}

.user {
  background: #111827;
  color: #ffffff;
  border-radius: 16px 16px 4px 16px;
}

/* ИНДИКАТОР ПЕЧАТИ BOT ТИПА */
.typing-indicator {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 14px 20px;
}

.typing-indicator .dot {
  width: 6px;
  height: 6px;
  background: #9ca3af;
  border-radius: 50%;
  animation: bounce 1.4s infinite ease-in-out both;
}

.typing-indicator .dot:nth-child(1) { animation-delay: -0.32s; }
.typing-indicator .dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes bounce {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1.0); }
}

/* БЛОК БЫСТРЫХ ПОДСКАЗОК (QUICK ACTIONS) */
.quick-actions-container {
  display: flex;
  gap: 6px;
  overflow-x: auto;
  padding: 12px 20px 4px 20px;
  background: #ffffff;
  border-top: 1px solid #f3f4f6;
  scrollbar-width: none;
}

.quick-actions-container::-webkit-scrollbar {
  display: none;
}

.action-chip {
  border: 1px solid #e5e7eb;
  background: #ffffff;
  color: #4b5563;
  padding: 8px 14px;
  border-radius: 50rem;
  cursor: pointer;
  font-size: 0.8rem;
  font-weight: 500;
  white-space: nowrap;
  transition: all 0.2s ease;
}

.action-chip:hover {
  border-color: #111827;
  color: #111827;
  background: #f9fafb;
  transform: translateY(-1px);
}

/* СТИЛИ ДЛЯ ВНУТРЕННЕЙ КНОПКИ ЗАКРЫТИЯ */
.close-action-wrapper {
  display: flex;
  justify-content: center;
  padding: 4px 20px 10px 20px;
  background: #ffffff;
}

.close-text-btn {
  background: transparent;
  border: none;
  color: #9ca3af;
  font-size: 0.8rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: color 0.2s ease;
}

.close-text-btn:hover {
  color: #4b5563;
}

/* НИЖНЯЯ ПАНЕЛЬ ВВОДА (FOOTER) */
.chat-footer {
  padding: 16px 20px;
  background: #ffffff;
  border-top: 1px solid #f3f4f6;
}

.input-group-custom {
  display: flex;
  align-items: center;
  background: #f3f4f6;
  border-radius: 14px;
  padding: 4px 6px 4px 14px;
  border: 1px solid transparent;
  transition: all 0.2s ease;
}

.input-group-custom:focus-within {
  background: #ffffff;
  border-color: #111827;
  box-shadow: 0 0 0 4px rgba(17, 24, 39, 0.05);
}

.input-group-custom input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 10px 0;
  outline: none;
  font-size: 0.9rem;
  color: #111827;
}

.input-group-custom input::placeholder {
  color: #9ca3af;
}

.send-message-btn {
  width: 38px;
  height: 38px;
  border: none;
  background: transparent;
  color: #9ca3af;
  border-radius: 10px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  transition: all 0.2s ease;
}

.send-message-btn.btn-active {
  background: #111827;
  color: #ffffff;
}

.send-message-btn.btn-active:hover {
  background: #1f2937;
}

/* АДАПТИВНОСТЬ ПОД МОБИЛЬНЫЕ ПЛАТФОРМЫ */
@media (max-width: 480px) {
  .assistant-window {
    width: 100%;
    height: 100%;
    right: 0;
    bottom: 0;
    border-radius: 0;
    border: none;
  }
  
  .chat-trigger-btn {
    bottom: 20px;
    right: 20px;
  }

  .mobile-back-btn {
    display: block; /* Показываем стрелочку назад */
  }

  .close-widget-btn {
    display: none; /* Прячем крестик справа, так как есть стрелка слева */
  }
}
</style>