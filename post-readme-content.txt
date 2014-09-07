## How is it dynamic?

The whole work is dependent on the data provided by `startupranking.com`. All the lists here are updated each day with the up-to-date information on `startupranking.com`. Here it follows:

1. Firstly, the list of supported countries(countries which have startups and are registered on the site) are crunched according to the list [startupranking.com/countries](http://www.startupranking.com/countries). This list is stored as a JSON file - `supported-countries.json`.

2. The `README.md` file is generated based on three things. Firstly, the data is fetched from the file `pre-readme-content.txt`, then the data collected from crawling the site is appended and finally, content from `post-readme-content.txt` is fetched to make this `README` file. This way, only scripts need to be fired each day and pushing the desired changes make the overall process very friendly and in sync with the site's information.

3. The data for Top 100 startups is shown in the `README` whereas list of startups for a particular country are present inside `countries/` folder.

## Contribution

1. Clone this repo.
2. Create a new branch(prefixing your `github username`). For eg. `softvar-fixed-typo` or `softvar-updated-list`.
3. Do the desired work.
4. Ignore steps 5 and 6 if there's only a typo-fix.
5. Run `fetch-countries.php` to update the supported list of countries.
6. Run `index.php` to fetch the data and Update the `corresponding files` and `README.md` file. Please wait since this script will be crunching a huge data and meanwhile writing the same using `Markdown syntax` to the corresponding files :)
7. Please verify your changes.
8. Only one commit for all the changed files.

    ```
    $ git add .
    $ git commit -m "Updated the list of startups"
    $ git push origin [branch-name]
    ```

9. Open a Pull Request(PR).
10. Please write a meaningful description for the PR and you're done.

## License

MIT (c) 2014, Varun Malhotra
[startupranking Privacy-policy](startupranking.com/corp/privacy-policy)