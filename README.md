# TASK: Create a simple Twitter-like app API

# 📍 Table of Contents
  * [Documentation](#documentation)
  * [Required](#required)
  * [Optional](#optional)
  * [Tests](#tests)
    + [Auth](#auth)
    + [Tweets](#tweets)
      - [Create](#create)
      - [Read](#read)
      - [Update](#update)
      - [Delete](#delete)
  *  [ToDos](#todos)

# 📖 Introduction 📖
Hi, welcome to my github! My name is James, and I make software 🗿.  Feel free to browse my repos to get a sense of how I code. I still have a long way to go, but I've learned so much already.   

You can find the documentation to the endpoints of this codebase on [this link](https://docs.google.com/spreadsheets/d/1QKhFJ-4LnNwW8spovTW6EZ4dwKlFmOTvUGCv1ZWWRb4/edit?usp=sharing). Similarly, you can find the exported Postman workspace file over on `resources/Postman/` directory.

Thank you for giving me the chance to work on this small test. I'll be improving on it in the coming weeks, and adding this to my portfolio.

## Documentation
- [x] Create API documentation using Postman or any other tools
- [x] Add Postman workspace file to laravel resources

## Required
- [x] Ability to register (required)
- [x] Ability to login/logout (required)
- [x] Create/update tweet (required)
- [x] Upload file attachment(s) to a tweet (required)
- [x] Delete tweet

## Optional
- [x] Follow/unfollow a user
- [x] List followed user's tweet
- [x] List suggested users follow
- [x] Cache user data when getting own profile

## Tests
### Auth
- [x] signup
- [x] login
### Tweets
#### Create
- [x] create tweet
- [x] unsuccessfully create tweet with bad parameters
- [x] unsuccessfully create tweet with incorrect (doesn't exist) replying_to parameter
#### Read
- [x] get a specific tweet
- [x] unsuccessfully get a specific tweet with wrong id
##### Update
- [x] update a tweet
- [x] unsuccessfully update tweet with wrong id
- [x] unsuccessfully update tweet with empty string as new_tweet_body param
- [x] unsuccessfully update tweet with new_tweet_body param > 280 chars
#### Delete
- [x] delete tweet
- [x] unsuccessfully delete tweet with bad parameters

## ToDos
- [ ] CI/CD Integration
