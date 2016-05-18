## Asset Publisher [![Build Status](https://travis-ci.org/realpage/asset-publisher.svg?branch=master)](https://travis-ci.org/realpage/asset-publisher)

This service responds to GitHub web hooks looking for semantically versioned tags and deploying minor and patch versions to a distributor (i.e. - Amazon S3).

## Amazon S3 (default)

Set the following environment variables

 * `AWS_KEY`
 * `AWS_SECRET`
 * `AWS_BUCKET`
 * `AWS_REGION`