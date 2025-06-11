
const currentUser = localStorage.getItem("currentUser");
let currentChatFriend = null;

document.addEventListener("DOMContentLoaded", () => {
  if (!currentUser) {
    window.location.href = "index.html";
    return;
  }

  fetch("friends.json")
    .then(res => res.json())
    .then(data => {
      const friends = data[currentUser] || [];
      const ul = document.getElementById("friend-list");
      ul.innerHTML = "";
      friends.forEach(friend => {
        const li = document.createElement("li");
        const span = document.createElement("span");
        span.textContent = friend + " ";
        const camBtn = document.createElement("button");
        camBtn.textContent = "📹";
        camBtn.style.marginRight = "5px";
        camBtn.onclick = () => window.location.href = `video.html?with=${friend}`;
        li.appendChild(span);
        li.appendChild(camBtn);
        li.onclick = () => openChat(friend);
        ul.appendChild(li);
      });
    })
    .catch(err => console.error("שגיאה בטעינת חברים:", err));
});

function openChat(friend) {
  currentChatFriend = friend;
  document.getElementById("chat-with").textContent = `צ'אט עם ${friend}`;
  document.getElementById("chat-box").style.display = "block";
  fetch(`load_chat.php?sender=${currentUser}&receiver=${friend}`)
    .then(res => res.text())
    .then(text => {
      const box = document.getElementById("messages");
      box.innerHTML = "";
      text.trim().split('\n').forEach(line => {
        if (line.startsWith("#")) {
          const date = document.createElement("div");
          date.className = "date-separator";
          date.textContent = line.replace("# ", "");
          box.appendChild(date);
          return;
        }
        const [time, rest] = line.split("|");
        const [sender, ...msgParts] = rest.split(":");
        const msg = msgParts.join(":");
        const div = document.createElement("div");
        div.className = sender === currentUser ? "message sent" : "message received";
        div.innerHTML = `<div class="text">${msg}</div><div class="time">${time}</div>`;
        box.appendChild(div);
      });
    });
}

function sendMessage() {
  const msg = document.getElementById("chat-input").value.trim();
  if (!msg) return;
  const data = new FormData();
  data.append("sender", currentUser);
  data.append("receiver", currentChatFriend);
  data.append("message", msg);
  fetch("chat.php", { method: "POST", body: data }).then(() => {
    openChat(currentChatFriend);
    document.getElementById("chat-input").value = "";
  });
}
