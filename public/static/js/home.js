const tasks = document.querySelectorAll(".task");

tasks.forEach(function (task) {
    const taskStatus = task.querySelector(".task-status");
    
    if (taskStatus.innerText == "completed") {
        task.querySelector(".task-title").classList.add("task-title-completed");
        task.querySelector(".task-description").classList.add("task-description-completed");
        task.querySelector(".task-status").classList.add("task-status-completed");
    }
});
