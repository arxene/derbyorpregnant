<h2>Add a food</h2>

<form action="quizSubmission.php" method="post">
  <div class="radio">
    <label>
      <input type="radio" name="submissionOption" value="derby" checked>
      Derby
    </label>
  </div>
  <div class="radio">
    <label>
      <input type="radio" name="submissionOption" value="pregnant">
      Pregnant
    </label>
  </div>
  
  <div class="form-group">
    <label for="name">Name*</label>
    <input type="text" class="form-control" name="submissionName" id="name" placeholder="Enter your derby name or real name here" required>
  </div>
    
  <div class="form-group">
    <label for="food">Food*</label>
    <textarea class="form-control" rows="3" name="submissionFood" id="food" placeholder="What was your weird food?" required></textarea>
  </div>
  
  <div class="form-group">
    <button type="submit" class="btn btn-primary">Submit Food</button>
  </div>
</form>
