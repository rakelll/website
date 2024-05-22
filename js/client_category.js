function makeToast(title, body_text) {

  if (!(document.getElementById("clientCategoryToasts"))) {
    const newToastContainer = document.createElement("div");
    newToastContainer.classList.add("toast-container", "top-0", "end-0", "p-2", "position-fixed");
    newToastContainer.setAttribute("id", "clientCategoryToasts");

    document.body.appendChild(newToastContainer);
  }

  const newToastContainer = document.getElementById("clientCategoryToasts");

  const toastId = (Math.random() + 1).toString(36).substring(2);


  const newToast = document.createElement("div");
  newToast.classList.add("toast");
  newToast.setAttribute("role", "alert");
  newToast.setAttribute("aria-live", "assertive");
  newToast.setAttribute("aria-atomic", "true");
  newToast.setAttribute("id", toastId);

  const newToastHeader = document.createElement("div");
  newToastHeader.classList.add("toast-header", "justify-content-between");

  const newToastHeaderTitle = document.createElement("strong");
  newToastHeaderTitle.textContent = title;

  const newToastHeaderCloseButton = document.createElement("button")
  newToastHeaderCloseButton.type = "button";
  newToastHeaderCloseButton.classList.add("btn-close");
  newToastHeaderCloseButton.setAttribute("data-bs-dismiss", "toast");
  newToastHeaderCloseButton.setAttribute("aria-label", "Close");

  newToastHeader.appendChild(newToastHeaderTitle);
  newToastHeader.appendChild(newToastHeaderCloseButton);

  const newToastBody = document.createElement("div");
  newToastBody.classList.add("toast-body");
  newToastBody.textContent = body_text;

  newToast.appendChild(newToastHeader);
  newToast.appendChild(newToastBody);

  newToastContainer.appendChild(newToast);

  const myToast = new bootstrap.Toast(`#${toastId}`);
  myToast.show();
}

function removeCategoryRequest(url, category_id) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Category Removed`, `The category "${response.category_name}" is removed.`);


        document.getElementById(`categoryRow${category_id}`).remove();
      } else {
        makeToast(`Category NOT Removed`, `There was an error removing the category "${response.category_name}".`)
      }
    }
  }

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  httpRequest.send(`category_id=${encodeURIComponent(category_id)}`);
}

function removeCategory(category_id) {
  removeCategoryRequest('client_category_action.php', category_id);
}

function changeCategoryRequest(url, category_id, new_category) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Category Changed`, `The category "${response.last_category}" is changed to "${response.category_name}".`);
      } else {
        makeToast(`Category NOT Changed`, `There was an error changed the category "${response.last_category}" to "${response.category_name}".`)
      }
    }
  };

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded",
  );
  httpRequest.send(`category_id=${encodeURIComponent(category_id)}&category_change=${encodeURIComponent(new_category)}`);
}

function editCategory(category_id) {
  const editButton = document.getElementById(`CategoryButton${category_id}`);
  const editSpan = document.getElementById(`CategorySpan${category_id}`);

  editButton.classList.replace("btn-outline-warning", "btn-outline-success");
  editButton.classList.replace("fa-pen-to-square", "fa-check");
  editButton.setAttribute("onclick", `saveCategoryChange(${category_id})`);

  editSpan.setAttribute("contenteditable", "true");
  editSpan.style.border = "1px solid gray";
}

function saveCategoryChange(category_id) {
  const categorySpan = document.getElementById(`CategorySpan${category_id}`).textContent.trim();

  changeCategoryRequest('client_category_action.php', category_id, categorySpan);

  const editButton = document.getElementById(`CategoryButton${category_id}`);
  const editSpan = document.getElementById(`CategorySpan${category_id}`);

  editButton.classList.replace("btn-outline-success", "btn-outline-warning");
  editButton.classList.replace("fa-check", "fa-pen-to-square");
  editButton.setAttribute("onclick", `editCategory(${category_id})`);

  editSpan.setAttribute("contenteditable", "false");
  editSpan.style.border = "none";
}
