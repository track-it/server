# TrackIt Web Client

## Instructions for commits

* Never commit directly on `master`. Always create a separate branch, with a name that is mapped to the issue you are working on. For example, when working on issue #39, then create a new branch with `git checkout -b [branch-name]` and name the branch `issue-39`.
* When you have commited and pushed changes on a development branch, make sure you have your code **reviewed** by someone in the team who have not written nor been helping you with the code. If possible, the code should also be black-box tested by someone without insight in the code and must be someone who did not review the code.
* When code review and tests have passed, you can merge your code into the master branch.
* As soon as your code has been merged to master and everything is working as intended, make sure you close the issue and delete the branch.

**NOTE:** To switch branches during development, use `git checkout [branch-name]`. Although, remember to either **stash** or **commit** your changes before it, or otherwise your changes will be moved to the branch you are switching to. To list your local branches, use `git branch`.

