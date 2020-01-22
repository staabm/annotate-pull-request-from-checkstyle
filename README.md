# Annotate a Github Pull Request based on a Checkstyle XML-report

Turns checkstyle based XML-Reports into Github Pull Request Annotations via the Checks API.

# Example Usage

`phpstan analyse --no-progress --error-format=checkstyle | vendor/bin/cs2pr`

`psalm --output-format=checkstyle | vendor/bin/cs2pr`
