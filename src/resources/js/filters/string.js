/**
 * Created by Xavier on 13/1/2018.
 */
export const filters = {
  ucword : word => word.split("").map((char, index) => index === 0 ? char.toUpperCase() : char).join(""),
  ucwords: words => words.split(" ").map(word => filters.ucword(word)).join(" "),
  isMetaContent: blocks => _.filter(blocks, block => block.identifier.indexOf('meta_') === 0),
  isNotMetaContent: blocks => _.filter(blocks, block => block.identifier.indexOf('meta_') !== 0),
}
