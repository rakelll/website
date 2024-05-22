function makeToast(title, body_text) {

  if (!(document.getElementById("clientGovernorateToasts"))) {
    const newToastContainer = document.createElement("div");
    newToastContainer.classList.add("toast-container", "top-0", "end-0", "p-2", "position-fixed");
    newToastContainer.setAttribute("id", "clientGovernorateToasts");

    document.body.appendChild(newToastContainer);
  }

  const newToastContainer = document.getElementById("clientGovernorateToasts");

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

function removeGovernorateRequest(url, governorate_id) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Governorate Removed`, `The governorate "${response.governorate_name}" is removed.`);


        document.getElementById(`governorateRow${governorate_id}`).remove();
      } else {
        makeToast(`Governorate NOT Removed`, `There was an error removing the governorate.`)
      }
    }
  }

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  httpRequest.send(`governorate_id=${encodeURIComponent(governorate_id)}`);
}

function removeGovernorate(governorate_id) {
  removeGovernorateRequest('governorate_action.php', governorate_id);
}

function changeGovernorateRequest(url, governorate_id, new_governorate) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Governorate Changed`, `The governorate "${response.last_governorate}" is changed to "${response.governorate_name}".`);
      } else {
        makeToast(`Governorate NOT Changed`, `There was an error changing the governorate.`);
      }
    }
  };

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded",
  );
  httpRequest.send(`governorate_id=${encodeURIComponent(governorate_id)}&governorate_name=${encodeURIComponent(new_governorate)}`);
}

function editGovernorate(governorate_id) {
  const editButton = document.getElementById(`GovernorateButton${governorate_id}`);
  const editSpan = document.getElementById(`GovernorateSpan${governorate_id}`);

  editButton.classList.replace("btn-outline-warning", "btn-outline-success");
  editButton.classList.replace("fa-pen-to-square", "fa-check");
  editButton.setAttribute("onclick", `saveGovernorateChange(${governorate_id})`);

  editSpan.setAttribute("contenteditable", "true");
  editSpan.style.border = "1px solid gray";
}

function saveGovernorateChange(governorate_id) {
  const governorateSpan = document.getElementById(`GovernorateSpan${governorate_id}`).textContent.trim();

  changeGovernorateRequest('governorate_action.php', governorate_id, governorateSpan);

  const editButton = document.getElementById(`GovernorateButton${governorate_id}`);
  const editSpan = document.getElementById(`GovernorateSpan${governorate_id}`);

  editButton.classList.replace("btn-outline-success", "btn-outline-warning");
  editButton.classList.replace("fa-check", "fa-pen-to-square");
  editButton.setAttribute("onclick", `editGovernorate(${governorate_id})`);

  editSpan.setAttribute("contenteditable", "false");
  editSpan.style.border = "none";
}
