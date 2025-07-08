document.getElementById("jobForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const form = this;
  const formData = new FormData(form);
fetch('submit_job.php', {
  method: 'POST',
  body: formData
})
.then(res => res.text())  // First get as plain text
.then(text => {
  console.log("Raw response:", text);  // Helpful for debugging
  let data;
  try {
    data = JSON.parse(text);
  } catch (err) {
    console.error("JSON Parse Error", err);
    alert("Invalid server response. Check console.");
    return;
  }

  if (data.status === 'success') {
    alert(data.message);
  } else if (data.status === 'limit_reached') {
    new bootstrap.Modal(document.getElementById("planModal")).show();
  } else {
    alert(data.message || "Unknown error occurred");
  }
});

});
