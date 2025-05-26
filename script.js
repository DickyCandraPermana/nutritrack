function showFlashMessage({ type = "success", messages = [] }) {
  if (!Array.isArray(messages)) messages = [messages];

  const styles = {
    success: {
      bg: "bg-green-100",
      text: "text-green-800",
      border: "border-green-300",
      icon: "text-green-600",
      svg: `<svg class="w-5 h-5 mr-3 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.54-9.54a.75.75 0 10-1.06-1.06L9 10.94 7.52 9.46a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l4-4z" clip-rule="evenodd"/>
            </svg>`,
    },
    error: {
      bg: "bg-red-100",
      text: "text-red-800",
      border: "border-red-300",
      icon: "text-red-600",
      svg: `<svg class="w-5 h-5 mr-3 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zM8.22 7.22a.75.75 0 011.06 0L10 7.94l.72-.72a.75.75 0 111.06 1.06L11.06 9l.72.72a.75.75 0 01-1.06 1.06L10 10.06l-.72.72a.75.75 0 01-1.06-1.06L8.94 9l-.72-.72a.75.75 0 010-1.06z" clip-rule="evenodd"/>
            </svg>`,
    },
    info: {
      bg: "bg-blue-100",
      text: "text-blue-800",
      border: "border-blue-300",
      icon: "text-blue-600",
      svg: `<svg class="w-5 h-5 mr-3 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zM9 8a1 1 0 102 0 1 1 0 00-2 0zm.75 2.75a.75.75 0 00-1.5 0v3.5a.75.75 0 001.5 0v-3.5z" clip-rule="evenodd"/>
            </svg>`,
    },
    warning: {
      bg: "bg-yellow-100",
      text: "text-yellow-800",
      border: "border-yellow-300",
      icon: "text-yellow-600",
      svg: `<svg class="w-5 h-5 mr-3 text-yellow-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.347 11.294c.75 1.335-.213 2.996-1.743 2.996H3.653c-1.53 0-2.492-1.66-1.743-2.996L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-.25-5.75a.75.75 0 00-1.5 0v3a.75.75 0 001.5 0v-3z" clip-rule="evenodd"/>
            </svg>`,
    },
  };

  const s = styles[type] || styles.success;
  const flashEl = document.createElement("div");
  flashEl.className = `flex items-start max-w-sm p-4 ${s.text} ${s.bg} ${s.border} border shadow-lg rounded-2xl animate-fade-in-down gap-3`;
  flashEl.innerHTML = `
    ${s.svg}
    <ul class="space-y-1 text-sm font-medium list-disc list-inside">
      ${messages.map((msg) => `<li>${escapeHtml(msg)}</li>`).join("")}
    </ul>
    <button onclick="this.parentElement.remove()" class="ml-auto ${
      s.icon
    } hover:opacity-80 focus:outline-none">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  `;
  document.getElementById("flash-container").appendChild(flashEl);
}

function escapeHtml(text) {
  const div = document.createElement("div");
  div.innerText = text;
  return div.innerHTML;
}
