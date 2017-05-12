## Asset Publisher [![Build Status](https://travis-ci.org/realpage/asset-publisher.svg?branch=master)](https://travis-ci.org/realpage/asset-publisher)

This service responds to GitHub webhooks looking for semantically versioned tags and deploying minor and patch versions (i.e. - v1.2/v1.2.3) to a distributor (i.e. - Amazon S3).

## Usage

Check out the [Docker repository](https://hub.docker.com/r/realpage/asset-publisher/) for the latest versions.

**Environment Variables**

 * `PRIVATE_KEY` - Used by the _CLI container_ to gain access to private repositories
 * `TOKEN` - A unique token to help abstract endpoints
 * `USE_NAMESPACES` - Deploy assets in a subdirectory base on the git repository's name (e.g. `https://s3.amazonaws.com/my-bucket/[REPO-NAME]/v1.0.0/...`)
 * `BUILD_PATH` - Relative path to the directory within the git repository that should be deployed to s3.  Defaults to `build`

**GitHub Webhook**

After you've deployed the docker container, add a webhook to the desired repo with the following:

 * Payload URL: http://[DOMAIN_OR_IP]/webhook/[ENV_VAR_TOKEN]/
 * Content-Type: application/json
 * Configure only **Release** events
 
**Triggering Deployment**

Simple add a new GitHub release with a semantically versioned tag and you're all set.

**Healthcheck**

`/healthcheck` provides a 200 OK response if container is healthy. 

## Amazon S3

 * `AWS_KEY`
 * `AWS_SECRET`
 * `AWS_BUCKET`
 * `AWS_REGION` (defaults to `us-east-1`
 
  > Despite the use of flysystem, distributors other than Amazon S3 are not supported at this time due to a limitation in uploading directories via flysystem.

## FAQ

**When using Cloudfront, why aren't my minor version (e.g. v1.1) assets being updated when a new patch version (e.g. v1.1.2) is released?**

CDN services like CloudFront cache assets for a certain timeframe (Cloudfront defaults to 24 hours) and only obtain fresh versions of files when the cache expires.  Assets won't be immediately updated unless a [cache invalidation request](http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html) is submitted.
