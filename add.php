<link rel="stylesheet" type="text/css" href="bootstrap.min.css">

<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="offset-md-3">
			
			<!-- creating the form -->
			<!-- apply encoding type to process file -->
			<form method="POST" action="add-original.php" enctype="multipart/form-data">

				<!-- creating image input file -->
				<div class="form-group">
					<label>Select image</label>
					<input type="file" name="image" class="form-control">
				</div>

				<!-- create submit button -->
				<input type="submit" class="btn btn-primary" value="Add overlay">

			</form>

		</div>
	</div>
</div>