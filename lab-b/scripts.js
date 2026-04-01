class Todo {
  constructor() {
    this.tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    this.searchText = "";
    this.draw();
    this.bindEvents();
  }

  draw() {
    const list = document.getElementById("todo-list");
    list.innerHTML = "";
    this.tasks
      .filter(task => task.text.toLowerCase().includes(this.searchText.toLowerCase()))
      .forEach((task, index) => {
        const li = document.createElement("li");
        let text = task.text;
        if (this.searchText.length >= 2) {
          const regex = new RegExp(`(${this.searchText})`, "gi");
          text = text.replace(regex, `<span class="highlight">$1</span>`);
        }
        li.innerHTML = `
                    <input type="checkbox">
                    <span class="task-text">${text}</span>
                    <span class="date">${task.date || ""}</span>
                    <button class="delete">X</button>
                `;
        li.querySelector(".delete").onclick = () => {
          this.remove(index);
        };
        li.querySelector(".task-text").onclick = (e) => {
          e.stopPropagation();
          this.editMode(li, task, index);
        };
        list.appendChild(li);
      });
    this.save();
  }

  add(text, date) {
    this.tasks.push({ text, date });
    this.draw();
  }

  remove(index) {
    this.tasks.splice(index, 1);
    this.draw();
  }

  editMode(li, task, index) {
    li.innerHTML = `
            <input type="text" value="${task.text}" id="edit-text">
            <input type="date" value="${task.date || ""}" id="edit-date">
        `;
    const textInput = li.querySelector("#edit-text");
    const dateInput = li.querySelector("#edit-date");

    const saveEdit = () => {
      const newText = textInput.value;
      const newDate = dateInput.value;
      this.tasks[index] = { text: newText, date: newDate };
      this.draw();
    };

    document.addEventListener("click", function handler(e) {
      if (!li.contains(e.target)) {
        saveEdit();
        document.removeEventListener("click", handler);
      }
    });
  }

  setSearch(text) {
    this.searchText = text.length >= 2 ? text : "";
    this.draw();
  }

  save() {
    localStorage.setItem("tasks", JSON.stringify(this.tasks));
  }

  bindEvents() {
    document.getElementById("add-button").addEventListener("click", () => {
      const text = document.getElementById("task-input").value;
      const date = document.getElementById("date-input").value;
      this.add(text, date);
      document.getElementById("task-input").value = "";
      document.getElementById("date-input").value = "";
    });
    document.getElementById("search").addEventListener("input", (e) => {
      this.setSearch(e.target.value);
    });
  }
}

document.todo = new Todo();
