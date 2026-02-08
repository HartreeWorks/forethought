---
title: "Evaluating the Infinite"
url: https://www.forethought.org/research/evaluating-the-infinite
---

# Evaluating the Infinite

[Toby Ord](https://www.forethought.org/people/toby-ord)

## Authors

21st September 2025


## Abstract

I present a novel mathematical technique for dealing with the infinities arising from divergent sums and integrals. It assigns them fine-grained infinite values from the set of hyperreal numbers in a manner that refines the standard theories of summation and integration. This has implications in statistics (helping us work with distributions whose mean or variance is infinite), decision theory (allowing comparison of options with infinite expected values), economics (allowing evaluation of infinitely long streams of utility without discounting), and ethics (allowing evaluation of infinite worlds). There are even implications for finite cases, as the ability to handle these infinities undermines a common argument for bounded utility and the discounting of future utility.

## Introduction

Decision theory, economics, and ethics all encounter serious technical problems when the value of something goes to infinity. This could be a prospect (such as the St Petersburg gamble), an unending stream of benefits, or a population. In all cases, we want to evaluate or compare things with infinitely many finite parts, even when the sum or integral diverges to infinity. These technical problems for infinite cases have led many in these fields to adopt controversial workarounds (such as bounded utility or pure time preference) which change how we rank alternatives even in finite cases. This is premature, for there are technical solutions to the technical problems.

Standard theories of summation and integration use the extended reals, which aren’t rich enough to represent our intuitions. They have only one positive infinite value (+∞+\\infty+∞) which forces all infinitely valuable things to be equal. The ordinal and cardinal numbers are also inadequate: as generalisations of natural numbers they are missing too much of the structure of the reals. But there is a very natural extension of the reals to infinite values (the _hyperreal numbers_) which is rich enough to allow a nonstandard theory of summation and integration that assigns fine-grained infinite values to the relevant sums and integrals.

## The hyperreal numbers

The hyperreals (∗R^\*\\mathbb{R}∗R) are an extension of the real numbers that includes both infinitesimal and infinite numbers (Robinson 1966, Keisler 1976, Goldblatt 1998). Just as real numbers can be defined in terms of sequences of rational numbers, so the hyperreal numbers can be defined in terms of sequences of real numbers. But there are two differences. We drop the requirement that the sequences are Cauchy-convergent (divergent sequences will allow for infinite numbers), and we use a finer grained distinction for when two sequences represent the same number (allowing for numbers that are infinitesimally different from each other).

For sequences to represent the same number, they don’t merely have to approach each other. They have to be exactly equal at a ‘large’ set of indices. The set of all indices (N\\mathbb{N}N) is considered large, as are all subsets of N\\mathbb{N}N that are only missing finitely many elements. So if two sequences are equal from some point onwards, they will be equal at a large set of indices and thus represent the same number. But to get the construction to work, we need as many large sets as possible. For every set of indices, either it, or its complement must be designated large. And we must do this in a consistent way that ensures every set containing a large set is large, and the intersection of two large sets is large.

A set of subsets of N\\mathbb{N}N that meets these conditions is called a _free ultrafilter_. There are many ways to add subsets so as to consistently meet these rules, but the axiom of choice is required to construct a particular example. By specifying the large sets of indices, the free ultrafilter, F\\boldsymbol{\\mathcal{F}}F, tells us how to partition the space of sequences of real numbers into equivalence classes, each of which will act as an individual hyperreal. In some sense, every free ultrafilter defines a different version of the hyperreals. But fortunately, if the continuum hypothesis holds then the properties of the hyperreals are independent (up to isomorphism) of the particular ultrafilter used.

For each real number rrr, there is a corresponding hyperreal ∗r^\*r∗r defined as the equivalence class containing ⟨r,r,r,…⟩\\langle r, r, r, \\dots\\rangle⟨r,r,r,…⟩ (denoted \[⟨r,r,r,…⟩\]\[\\langle r, r, r, \\ldots\\rangle\]\[⟨r,r,r,…⟩\]). But there are also more exotic hyperreals such as \[⟨1,2,3,…⟩\]\[\\langle 1, 2, 3, \\ldots \\rangle\]\[⟨1, 2, 3,…⟩\] — an infinite number denoted ω\\omegaω which will play a key role in the theory of hyperreal summation and integration.[1](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-2) There are also infinitesimal numbers such as \[⟨1,12,13,…⟩\]\\left\[ \\left\\langle 1, \\frac12, \\frac13, \\ldots \\right\\rangle \\right\]\[⟨1,21​,31​,…⟩\], which is equal to 1ω\\frac1\\omegaω1​. And there is a great diversity of other numbers such as:

−ω2,−ω+5,−12ω,1ω2,π−1ω,ω,ω2,ω+1ω,πω,ω3,eω,ω!\\displaystyle -\\omega^2, \\; -\\omega+5, \\; -\\frac{1}{2 \\omega}, \\; \\frac{1}{\\omega^2}, \\; \\pi - \\frac1\\omega, \\; \\sqrt{\\omega}, \\; \\frac\\omega2, \\; \\omega+ \\frac1\\omega, \\; \\pi \\omega, \\; \\omega^3, \\; e^{\\omega}, \\; \\omega!−ω2,−ω+5,−2ω1​,ω21​,π−ω1​,ω​,2ω​,ω+ω1​,πω,ω3,eω,ω!

For any function fff that can be applied to a real number, we can create a transferred version, ∗f^\*f∗f, that applies to any hyperreal, x(=\[⟨x1,x2,x3,…⟩\])x \\; (= \[\\langle x\_1, x\_2, x\_3, \\ldots \\rangle\])x(=\[⟨x1​,x2​,x3​,…⟩\]), by defining ∗f(x)=\[⟨f(x1),f(x2),f(x3),…⟩\]^\*f(x) = \[\\langle f(x\_1), f(x\_2), f(x\_3), \\ldots \\rangle\]∗f(x)=\[⟨f(x1​),f(x2​),f(x3​),…⟩\]. The same is true for functions of more than one argument and for properties and relations. When there is no danger of ambiguity, we usually drop the ∗^\*∗ symbol. For example, the hyperreal version of +++ is defined as x+y=\[⟨x1+y1,x2+y2,x3+y3,…⟩\]x + y = \[\\langle x\_1 + y\_1, x\_2 + y\_2, x\_3 + y\_3, \\ldots \\rangle\]x+y=\[⟨x1​+y1​,x2​+y2​,x3​+y3​,…⟩\].

A key property of the hyperreals is that they satisfy the _transfer principle_. This says that every first-order statement which is true for the reals is true for the hyperreals too. For example, because +++ is commutative on the reals, its hyperreal version is commutative on the hyperreals.

## Hyperreal summation and integration

To create a hyperreal theory of summation and integration, we simply generalise the finite sum and definite integral to allow their bounds (aaa and bbb) to take hyperreal values:[2](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-3)

∗∑i=abf(x)=def\[⟨∑i=a1b1f(x),∑i=a2b2f(x),∑i=a3b3f(x),…⟩\]\\displaystyle \* \\sum^b\_{i=a} f(x) \\; \\overset{\\small \\text{def}}{=} \\; \\left\[ \\left\\langle \\sum^{b\_1}\_{i = a\_1} f(x), \\; \\sum^{b\_2}\_{i = a\_2} f(x), \\; \\sum^{b\_3}\_{i = a\_3} f(x), \\ldots \\right\\rangle \\right\]∗i=a∑b​f(x)=def\[⟨i=a1​∑b1​​f(x),i=a2​∑b2​​f(x),i=a3​∑b3​​f(x),…⟩\]

∗∫abf(x)dx=def\[⟨∫a1b1f(x)dx,∫a2b2f(x)dx,∫a3b3f(x)dx,…⟩\]\\displaystyle \* \\int^b\_a f(x) \\; dx \\; \\overset{\\small \\text{def}}{=} \\; \\left\[ \\left\\langle \\int^{b\_1}\_{a\_1} f(x) \\; dx, \\; \\int^{b\_2}\_{a\_2} f(x) \\; dx, \\; \\int^{b\_3}\_{a\_3} f(x) \\; dx, \\ldots \\right\\rangle \\right\]∗∫ab​f(x)dx=def\[⟨∫a1​b1​​f(x)dx,∫a2​b2​​f(x)dx,∫a3​b3​​f(x)dx,…⟩\]

Then we redefine how sums and integrals up to infinity are to be understood, replacing the standard limit-based definitions with these new constructs applied to the hyperreal number ω\\omegaω:

∑i=a∞f(x)=def∗∑i=aωf(x)\\displaystyle \\sum^{\\infty}\_{i=a} f(x) \\; \\overset{\\small \\text{def}}{=} \\; \* \\sum^{\\omega}\_{i=a} f(x)i=a∑∞​f(x)=def∗i=a∑ω​f(x)

∫a∞f(x)dx=def∗∫aωf(x)dx\\displaystyle \\int^{\\infty}\_a f(x) \\; dx \\; \\overset{\\small \\text{def}}{=} \\; \* \\int^{\\omega}\_a f(x) \\; dx∫a∞​f(x)dx=def∗∫aω​f(x)dx

We can similarly redeﬁne integrals that go to −∞−\\infty−∞ or end at a singular point, sss:

∫−∞bf(x)dx=def∗∫aωf(x)dx\\displaystyle \\int^b\_{-\\infty} f(x) \\; dx \\; \\overset{\\small \\text{def}}{=} \\; \* \\int^{\\omega}\_a f(x) \\; dx∫−∞b​f(x)dx=def∗∫aω​f(x)dx

∫asf(x)dx=def∗∫as−1ωf(x)dx\\displaystyle \\int^s\_a f(x) \\; dx \\; \\overset{\\small \\text{def}}{=} \\; \* \\int^{s - \\frac1\\omega}\_a f(x) \\; dx∫as​f(x)dx=def∗∫as−ω1​​f(x)dx

∫sbf(x)dx=def∗∫s+1ωbf(x)dx\\displaystyle \\int^b\_s f(x) \\; dx \\; \\overset{\\small \\text{def}}{=} \\; \* \\int^b\_{s + \\frac1\\omega} f(x) \\; dx∫sb​f(x)dx=def∗∫s+ω1​b​f(x)dx

That’s it.

This new theory of summation and integration produces extremely intuitive answers to familiar sums and integrals:

1+1+1+…=∑i=1∞1=∗∑i=1ω1=\[⟨∑i=111,∑i=121,∑i=131,…⟩\]=\[⟨1,2,3,…⟩\]=ω\\displaystyle 1+1+1+ \\ldots \\; = \\; \\sum^{\\infty}\_{i=1} 1 = \* \\sum^{\\omega}\_{i=1} 1 = \\left\[ \\left\\langle \\sum^1\_{i=1} 1, \\sum^2\_{i=1} 1, \\sum^3\_{i=1} 1, \\ldots \\right\\rangle \\right\] = \[\\langle 1,2,3,\\ldots \\rangle\] = \\boldsymbol{\\omega}1+1+1+…=i=1∑∞​1=∗i=1∑ω​1=\[⟨i=1∑1​1,i=1∑2​1,i=1∑3​1,…⟩\]=\[⟨1,2,3,…⟩\]=ω

2+2+2+…=∑i=1∞2=∗∑i=1ω2=\[⟨∑i=112,∑i=122,∑i=132,…⟩\]=\[⟨2,4,6,…⟩\]=2ω\\displaystyle 2+2+2+ \\ldots \\; = \\; \\sum^{\\infty}\_{i=1} 2 = \* \\sum^{\\omega}\_{i=1} 2 = \\left\[ \\left\\langle \\sum^1\_{i=1} 2, \\sum^2\_{i=1} 2, \\sum^3\_{i=1} 2, \\ldots \\right\\rangle \\right\] = \[\\langle 2,4,6,\\ldots \\rangle\] = \\boldsymbol{2\\omega}2+2+2+…=i=1∑∞​2=∗i=1∑ω​2=\[⟨i=1∑1​2,i=1∑2​2,i=1∑3​2,…⟩\]=\[⟨2,4,6,…⟩\]=2ω

1+2+3+…=∑i=1∞i=∗∑i=1ωi=\[⟨∑i=11i,∑i=12i,∑i=13i,…⟩\]=\[⟨1,3,6,…⟩\]=ω(ω+1)2\\displaystyle 1+2+3+ \\ldots \\; = \\; \\sum^{\\infty}\_{i=1} i = \* \\sum^{\\omega}\_{i=1} i = \\left\[ \\left\\langle \\sum^1\_{i=1} i, \\sum^2\_{i=1} i, \\sum^3\_{i=1} i, \\ldots \\right\\rangle \\right\] = \[\\langle 1,3,6,\\ldots \\rangle\] = \\boldsymbol{\\frac{\\omega(\\omega+1)}{2}}1+2+3+…=i=1∑∞​i=∗i=1∑ω​i=\[⟨i=1∑1​i,i=1∑2​i,i=1∑3​i,…⟩\]=\[⟨1,3,6,…⟩\]=2ω(ω+1)​

Note how the value of 2+2+2+…2 + 2 + 2 + \\ldots2 + 2 + 2 +… is exactly twice the size of 1+1+1+…1 + 1 + 1 + \\ldots1 + 1 + 1 +… and how the value of 1+2+3+…1 + 2 + 3 + \\ldots1 + 2 + 3 +… is the triangular number formula applied to ω\\omegaω. Because the starred sum (∗∑\\* \\sum∗∑) is defined element-wise in terms of the finite sum, it inherits all its first-order properties such as linearity and additivity. And whenever a first-order property (like the value of a sum satisfying the triangular number formula) holds for finite sums up to every finite bound, it will hold at infinity too.

Integrals behave similarly:

![Mathematical equation showing the hyperreal integral of 1 from 0 to infinity equals omega, expressed as a sequence of definite integrals with increasing upper bounds](https://images.ctfassets.net/4owxfjx3z3if/5DEY3Nl22RGCUWivB4jgXa/b0545b92f938fe0ed26d817a7b34fc13/hyperreal-integral-equation-omega.png?w=3840&q=75&fm=webp)


![Mathematical equation showing the hyperreal integral of 2 from 0 to infinity equals 2 omega, demonstrating that doubling the constant function doubles the infinite integral value](https://images.ctfassets.net/4owxfjx3z3if/32eelqMqAp27uILooyMii1/0ae5817fa9456bcf0a1501592a5279ba/hyperreal-integral-equation-two-omega.png?w=3840&q=75&fm=webp)


![Mathematical equation showing the hyperreal integral of x from 0 to infinity equals omega squared over 2, with shaded triangular area illustrating quadratic growth in the infinite integral](https://images.ctfassets.net/4owxfjx3z3if/tiESLL9siZpXL6DIVvuGO/cc270826969e4064f558a48c8e0f061a/hyperreal-integral-equation-x-omega-squared.png?w=3840&q=75&fm=webp)


They also inherit first-order properties such as linearity and additivity. Perhaps most importantly, they inherit the Fundamental Theorem of Calculus.

**Theorem.** The Fundamental Theorem of Calculus applies to the starred integral.

**Proof:**

∗∫abf(x)dx=\[⟨∫a1b1f(x)dx,∫a2b2f(x)dx,∫a3b3f(x)dx,…⟩\]\\displaystyle \* \\int^b\_a f(x) \\; dx = \\left\[ \\left\\langle \\int^{b\_1}\_{a\_1} f(x) \\; dx, \\int^{b\_2}\_{a\_2} f(x) \\; dx, \\int^{b\_3}\_{a\_3} f(x) \\; dx, \\ldots \\right\\rangle \\right\]∗∫ab​f(x)dx=\[⟨∫a1​b1​​f(x)dx,∫a2​b2​​f(x)dx,∫a3​b3​​f(x)dx,…⟩\]

=\[⟨F(b1)−F(a1),F(b2)−F(a2),F(b3)−F(a3),…⟩\]\\displaystyle \\qquad\\qquad\\quad\\;\\;\\; = \\left\[ \\left\\langle F(b\_1) - F(a\_1), F(b\_2) - F(a\_2), F(b\_3) - F(a\_3), \\ldots \\right\\rangle \\right\]=\[⟨F(b1​)−F(a1​),F(b2​)−F(a2​),F(b3​)−F(a3​),…⟩\]

=\[⟨F(bn)⟩\]−\[⟨F(an)⟩\]\\displaystyle \\qquad\\qquad\\quad\\;\\;\\; = \\left\[ \\left\\langle F(b\_n)\\right\\rangle \\right\] - \\left\[ \\left\\langle F(a\_n)\\right\\rangle \\right\]=\[⟨F(bn​)⟩\]−\[⟨F(an​)⟩\]

=F(b)−F(a)\\displaystyle \\qquad\\qquad\\quad\\;\\;\\; = F(b) - F(a)=F(b)−F(a)

**Corollaries:**

∫a∞f(x)dx=F(ω)−F(a)\\displaystyle \\int^{\\infty}\_a f(x) \\; dx = F(\\omega) - F(a)∫a∞​f(x)dx=F(ω)−F(a)

∫−∞bf(x)dx=F(b)−F(−ω)\\displaystyle \\int^b\_{-{\\infty}} f(x) \\; dx = F(b) - F(-\\omega)∫−∞b​f(x)dx=F(b)−F(−ω)

∫asf(x)dx=F(s−1ω)−F(a)\\displaystyle \\int^s\_a f(x) \\; dx = F \\left(s - \\frac1\\omega \\right) - F(a)∫as​f(x)dx=F(s−ω1​)−F(a)

∫sbf(x)dx=F(b)−F(s+1ω)\\displaystyle \\int^b\_s f(x) \\; dx = F(b) - F \\left(s + \\frac1\\omega \\right)∫sb​f(x)dx=F(b)−F(s+ω1​)

We can use the Fundamental Theorem of Calculus to solve improper integrals:[3](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-4)

![Mathematical equation showing the hyperreal integral of 1/x from 1 to infinity equals log omega, with shaded area under hyperbolic curve demonstrating application of Fundamental Theorem of Calculus](https://images.ctfassets.net/4owxfjx3z3if/2I0Pcif0SDCx5RVzXw3337/73228fcf427cbda95a9bebbca9fcee61/hyperreal-integral-equation-one-over-x-upper.png?w=3840&q=75&fm=webp)


![Mathematical equation showing the hyperreal integral of 1/x from 0 to 1 equals log omega, with shaded area under hyperbolic curve near vertical asymptote at zero also yielding log omega](https://images.ctfassets.net/4owxfjx3z3if/5EtFqEk0l6E9jWIgervtWC/e46d85093b5e3be9245010d848c9c540/hyperreal-integral-equation-one-over-x-lower.png?w=3840&q=75&fm=webp)


We can even directly solve integrals where both bounds are improper:

![Mathematical equation showing the hyperreal integral of 1/x from 0 to infinity equals 2 log omega, with shaded area under entire hyperbolic curve demonstrating the doubly improper integral handled with hyperreal bounds](https://images.ctfassets.net/4owxfjx3z3if/6s9WsigsXnURrpjFlpBtWP/7457d984baedf1b19e2fcf83b85e150f/hyperreal-integral-equation-one-over-x-full.png?w=3840&q=75&fm=webp)


This new theory of summation and integration _refines_ the standard theory:[4](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-5)

1. Whenever the standard theory assigns a real number rrr to a sum or integral, the hyperreal theory assigns it rrr or a value infinitesimally close to rrr.

2. When the standard theory assigns a sum or integral the value +∞+\\infty+∞, the hyperreal theory may assign it a more precise positive infinite value (and respectively for negative values).


As (1) suggests, convergent sums and integrals are often assigned values that are infinitesimally different to their standard values. For example:

∑i=1∞2−i=1−12ω\\displaystyle \\sum^{\\infty}\_{i=1} 2^{-i} \\; = \\; 1 - \\frac{1}{2^{\\omega}}i=1∑∞​2−i=1−2ω1​

Whether this infinitesimal discrepancy is a bug or feature depends on the application. The usual proofs that this sum equals 1 rely on the principle that the sum must be within ϵ\\epsilonϵ of 1 for every real ϵ>0\\epsilon \\gt 0ϵ>0 — i.e. infinitesimally close to 1. In the real numbers the only such number is 1 itself. But in the hyperreals, it is perfectly consistent for this to sum to a number infinitesimally close to 1. We might see this as generalising the idea that summing finitely many terms leaves a finite discrepancy from 1 — summing infinitely many terms leaves a corresponding infinitesimal discrepancy.

In some applications this discrepancy is meaningful; indeed essential. For example, the value above can be used to represent the probability that an infinite sequence of coin tosses comes up tails at some point — a near certainty, but not guaranteed. Along these lines, a promising non-standard probability theory that includes infinitesimal probabilities can be developed.

In contexts where the infinitesimal discrepancies are unwanted, they can easily be rounded off. There is a unique closest real to every finite hyperreal (its _shadow_) and we can optionally adjust the definition of the hyperreal sum and integral to take the shadow.[5](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-6) This version of the theory hews even closer to the standard theory.

In cases where the standard sum or integral diverges, the precise value of the hyperreal sum or integral sometimes depends on which ultrafilter was used to construct the hyperreals. For example, consider the poorly behaved sum 1−2+3−4+…1 − 2 + 3 − 4 + \\ldots1−2+3−4+…. On the standard theory this diverges, but not to +∞+\\infty+∞ nor to −∞−\\infty−∞. Since the partial sums alternate in value but increase in absolute size, the standard sum of the series is undefined. What does the hyperreal theory say?

1−2+3−4+…=∑i=1∞i(−1)i−1=\[⟨1,−1,2,−2,3,−3,…⟩\]\\displaystyle 1 - 2 + 3 - 4 + \\ldots \\; = \\; \\sum^{\\infty}\_{i=1} i(-1)^{i-1} \\; = \\; \[\\langle 1, -1, 2, -2, 3, -3, \\ldots \\rangle\]1−2+3−4+…=i=1∑∞​i(−1)i−1=\[⟨1,−1,2,−2,3,−3,…⟩\]

Which hyperreal is this? If the set of odd numbers is in the ultrafilter, then this is the same number as \[⟨1,1.5,2,2.5,3,3.5,…⟩\]=ω2+12\[\\langle 1, \\; 1.5, \\; 2, \\; 2.5, \\; 3, \\; 3.5, \\ldots \\rangle\] = \\frac\\omega2 + \\frac12\[⟨1,1.5,2,2.5,3,3.5,…⟩\]=2ω​+21​ (since both representative sequences are identical on their odd elements). If the set of odd numbers isn’t in the ultrafilter, then the evens must be. In that case, that the sum is equal to \[⟨−0.5,−1,−1.5,−2,−2.5,−3,…⟩\]=−ω2\[\\langle −0.5, −1, −1.5, −2, −2.5, −3, \\ldots \\rangle\] = − \\frac\\omega2\[⟨−0.5,−1,−1.5,−2,−2.5,−3,…⟩\]=−2ω​.

Less badly behaved sums and integrals can also have results that depend on the ultrafilter. For example, the integral:

∫0∞1+sin⁡(x)dx\\displaystyle \\int^{\\infty}\_0 1 + \\sin(x) \\; dx∫0∞​1+sin(x)dx

standardly diverges to +∞+\\infty+∞. On the hyperreal theory of integration, it can take on values ranging from ω\\omegaω to ω+2\\omega + 2ω+2, depending on the ultrafilter. In both cases, the ultrafilter-dependence is being caused by a kind of oscillatory behaviour in the partial sums.

There are several ways to proceed. The most modest option is to say that any sum or integral whose value depends on the ultrafilter is undefined (or, if it is determinately infinite, that it equals +∞+\\infty+∞ or −∞−\\infty−∞). Then there is no more problem than on the standard theories, which also decline to say any more than this about such sums and integrals.

The boldest option is to use a particular ultrafilter. Each ultrafilter gives its own theory of summation and integration with its own consistent set of answers to such sums — which one we use is up to us. This is consistent, but not entirely satisfactory. Since the ultrafilter cannot be finitely specified, we can’t quite choose a specific theory and are left with some arbitrariness and unknowability in the values of sums and integrals. But given an ultrafilter, we do get a theory on which _every_ sum and integral evaluates to some particular hyperreal.

Between these modest and bold options is a middle path where we divide claims about infinite summation and integration into those that are determinately true (true for all ultrafilters), determinately false (false for all ultrafilters), or indeterminate (true for some; false for others). For example, if we denote the sum 1−2+3−4+…1 − 2 + 3 − 4 + \\ldots1−2+3−4+… as SSS, it is determinately true that:

- SSS is not finite

- ∣S∣<ω\\lvert S \\rvert \\lt \\omega∣S∣<ω

- S∈{−ω2,ω2+12}S \\in \\left\\{ - \\frac\\omega2, \\;\\frac\\omega2 + \\frac12 \\right\\}S∈{−2ω​,2ω​+21​}

- S−S=0S - S = 0S−S=0


But it is indeterminate whether S>0S \\gt 0S>0.

This is the approach I’ll adopt in this paper.

Note that even in cases like these where the value of a sum (or integral) is ultrafilter-dependant, the change in value coming from modifications of that sum usually doesn’t depend on the ultrafilter. For example, if we add 7 to one of the terms in the sum SSS, calling this new sum S’S’S’, then S’>SS’ \\gt SS’>S on all ultrafilters and S’−S=7S’ − S = 7S’−S=7 on all ultrafilters.

It may be possible to go further and resolve some (or all?) of the indeterminacies in a non-arbitrary way. For example, intuitively half the ultrafilters would include the odd numbers and half the even numbers. If so, we might be able to form an average across all ways of specifying the hyperreals. In this case, the average value for our alternating sum SSS is 14\\frac1441​. Interestingly, this is precisely the value assigned to this series by Abel summation, Euler summation and Borel summation. The same holds for several other divergent series and suggests there might be a way of simultaneously generalising those theories of summation, assigning fine-grained infinite values where appropriate, and avoiding some of the indeterminacy. However, there are challenges in defining an appropriate average, and I will have to leave this as a suggestive possibility.

Before we proceed with applications of the theory, it is worth noting that this is an unusual use of the hyperreals. While they were developed to put Leibniz’s infinitesimal calculus on a rigorous footing and are frequently applied to summation and integration, the field of non-standard analysis doesn’t assign hyperreal values to sums or integrals. Instead, non-standard analysis uses hyperreals as an alternative means for proving the standard results of analysis (which are expressed in terms of extended reals). Because it will ultimately round off finite results to the nearest real number and infinite results to +∞+\\infty+∞ or −∞−\\infty−∞ , non-standard analysis avoids the need to choose a particular hyperreal (such as ω\\omegaω) for the bounds on its sums and integrals and avoids the ultrafilter-dependence. However, it also passes up the opportunity to go beyond the standard results and to _refine_ or _extend_ the theory of analysis.

## Numerosity

The hyperreal theory of infinite summation also provides us with a useful way of measuring the sizes of infinite sets of numbers. We can assign each set of integers a hyperreal value, called its _numerosity_ (Błaszczyk 2021), by simply summing the index function for the set. For example, we have seen that the numerosity of the positive integers is:

∑i=1∞1=ω\\displaystyle \\sum^{\\infty}\_{i=1} 1 = \\omegai=1∑∞​1=ω

The numerosity of the entire set of integers is:

∑i=−∞∞1=\[⟨∑i=−111,∑i=−221,∑i=−331,…⟩\]=\[⟨3,5,7,…⟩\]=2ω+1\\displaystyle \\sum^{\\infty}\_{i=-\\infty} 1 = \\left\[ \\left\\langle \\sum^1\_{i=-1} 1, \\sum^2\_{i=-2} 1, \\sum^3\_{i=-3} 1, \\ldots \\right\\rangle \\right\] = \\left\[ \\left\\langle 3,5,7,\\ldots \\right\\rangle \\right\] \\; = \\; 2\\omega+1i=−∞∑∞​1=\[⟨i=−1∑1​1,i=−2∑2​1,i=−3∑3​1,…⟩\]=\[⟨3,5,7,…⟩\]=2ω+1

Which is equal to the numerosity of the positive integers (ω\\omegaω), plus the numerosity of the negative integers (ω\\omegaω), plus the numerosity of the set containing zero (1). The numerosity of the set of even numbers is ⌊ω2⌋\\left\\lfloor \\frac\\omega2 \\right\\rfloor⌊2ω​⌋ while the numerosity of the set of odd numbers is ⌈ω2⌉\\left\\lceil \\frac\\omega2 \\right\\rceil⌈2ω​⌉.[6](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-7) The numerosity of the square numbers is ⌊ω⌋\\lfloor \\sqrt{\\omega} \\rfloor⌊ω​⌋ while the numerosity of the non-square numbers is ⌈ω−ω⌉\\lceil \\omega - \\sqrt{\\omega} \\rceil⌈ω−ω​⌉. More generally, if XXX is a strict subset of YYY, XXX has a lower numerosity than YYY. And if a collection of subsets XiX\_iXi​ partition a set YYY, then the numerosity of YYY equals the sum of the numerosities of the XiX\_iXi​.

These are intuitively desirable properties for a conception of size, but we have become accustomed to their absence. The familiar way of measuring set sizes via bijections, assigns all these sets the same cardinal number, ℵ0\\aleph\_0ℵ0​. Neither way is more correct — they just represent different aspects of our intuitive conception of size as applied to infinite sets. They are complementary approaches, and it is good to have both in our toolkit.

As we will see, the hyperreal theory for sums and integrals often provides different answers to the Cantorian approach of cardinal numbers. The Cantorian reliance on bijections means it deliberately ignores the internal structure of a set of numbers and so provides a more abstracted, coarse-grained, concept of size. It isn’t designed to represent the intuitive idea that the set of square numbers is becoming more and more sparse — but numerosities capture this. They even tell us that the proportion of natural numbers which are square numbers is just the ratio of these numerosities, ⌊ω⌋/ω\\lfloor \\sqrt{\\omega}\\rfloor/\\omega⌊ω​⌋/ω— an infinitesimal fraction, yet greater than zero.

## Infinite Expectations

In probability theory and decision theory, distributions whose means diverge to infinity cause substantial problems. For example, consider the St Petersburg gamble (Bernoulli 1738), where there is a 1 in 2n2^n2n chance of gaining utility[7](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-8)nnn, for all positive integers nnn. Its expected value standardly diverges to +∞+\\infty+∞. This same expected value is assigned to a one-half chance of receiving a St Petersburg gamble, or to a St Petersburg gamble where all utilities have been doubled, and so on. This makes it impossible to compare such divergent distributions by their expected values, and violates important axioms of decision theory (such as preferring a distribution that dominates another and preferring higher chances of an option when it is better than its alternative).

To escape this problem, many decision theorists have concluded it is irrational to allow unbounded utilities. This forces all distributions to have finite expected value, sidestepping this problem. But it does so at the high price of requiring us to change our values — even in finite cases.[8](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-9) Other decision theorists have retained unbounded utilities and developed novel systematic ways of ordering these prospects, but have given up on assigning them numerical expected values.[9](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-10)

The hyperreal theory of summation and integration can be used to assign precise infinite expectations/means to many probability distributions whose means standardly diverge to +∞+\\infty+∞ or −∞−\\infty−∞. One can do this by simply taking the standard formula for the mean (the sum or integral of xxx times f(x)f(x)f(x)) and applying the hyperreal interpretation of summation/integration. According to the hyperreal theory, the expectation of the St Petersburg gamble is:[10](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-11)

∑i=1∞i⋅P(i)=\[⟨0,1,1,2,2,2,2,3,…⟩\]=\[⟨⌊log⁡2(n)⌋⟩\]=⌊log⁡2(ω)⌋\\displaystyle \\sum^{\\infty}\_{i=1} i \\cdot P(i) = \[\\langle 0,1,1,2,2,2,2,3,\\ldots \\rangle\] = \[\\langle \\lfloor \\log\_2(n) \\rfloor \\rangle\] = \\lfloor \\log\_2(\\omega) \\rfloori=1∑∞​i⋅P(i)=\[⟨0,1,1,2,2,2,2,3,…⟩\]=\[⟨⌊log2​(n)⌋⟩\]=⌊log2​(ω)⌋

This is infinite, but much smaller than ω\\omegaω. And a probability ppp of receiving a St Petersburg gamble is assigned expected value p⌊log⁡2(ω)⌋p \\lfloor \\log\_2(\\omega) \\rfloorp⌊log2​(ω)⌋ as we would hope.

The value ⌊log⁡2(ω)⌋\\lfloor \\log\_2(\\omega) \\rfloor⌊log2​(ω)⌋ is an example of what I call a _lesser infinity_ — an infinite number that is less than kωk \\omegakω for every real-valued kkk. This is an important class of infinite value that is absent from most well-known infinite number systems. All probability distributions over finite numbers (such as the reals) have expectations that are at best lesser infinities.[11](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-12) The St Petersburg gamble (and all other distributions over the reals) are thus beaten by any positive real chance of receiving ω\\omegaω value. If the value at stake in Pascal’s wager (say, an eternity in heaven) is taken to be kωk \\omegakω, and its probability is ppp, then its EV is pkωpk \\omegapkω. This is also an infinite value: one worth much more than the ⌊log⁡2(ω)⌋\\lfloor \\log\_2(\\omega) \\rfloor⌊log2​(ω)⌋ of the St Petersburg gamble, albeit much less than a guarantee of kωk \\omegakω value.

Note that this relationship between a lesser infinite expected value and a regular infinite expected value violates the continuity axiom of expected utility theory. Indeed one can construct a dense hierarchy of probability distributions with expected values of the form ωk\\omega^kωk for 0<k<10 \\lt k \\lt 10<k<1 where any non-zero real chance of a draw from a distribution with higher kkk beats any chance of a draw from one with lower kkk. To proceed, one either needs to either give up the continuity axiom or express it in a way that allows for the probabilities to range over infinitesimal hyperreal values. Both options seem reasonable given that in this situation some alternatives really do appear to be worth infinitely more than others.

This new approach reveals a wide range of values for distributions with infinite mean. Where the standard approach treats all distributions with positive infinite mean as equivalent, on the hyperreal approach they possess an even greater range of values than do distributions with finite means. The EVs of two different prospects can have an infinite difference or even an infinite ratio. And this can be true even if the prospects just range over the reals. This suggests that treating all prospects with infinite mean as equivalent could be as much of a mistake as treating all prospects with finite mean as equivalent.

One can also use hyperreal summation and integration to assign fine-grained infinite values to higher moments of a distribution, such as its variance. For example, the Cauchy distribution (whose mean and variance are standardly undefined) can be shown to have mean zero and variance ≈2ωπ−1\\approx \\frac{2\\omega}{\\pi} − 1≈π2ω​−1. This may allow one to analyse such distributions using statistical tools that standardly break when the variance is infinite. This could be important since the difficulty in working with some of these distributions often leads to them being replaced for reasons of convenience with distributions that have thinner tails — and these systematically underestimate outlier events.[12](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-13)

## Infinite Futures

In economics it is common to evaluate policies via the stream of utilities they would produce at future times. For finite streams a natural (and temporally impartial) method is to take the sum of these utilities (or in continuous time, the integral). But for unbounded streams (represented as infinite sequences of real numbers) this typically diverges to infinity, causing problems for the analysis. For example the streams of utilities given by ⟨1,1,1,…⟩\\langle 1, 1, 1, \\ldots \\rangle⟨1,1,1,…⟩ and ⟨2,2,2,…⟩\\langle 2, 2, 2, \\ldots \\rangle⟨2,2,2,…⟩ both standardly sum to +∞+\\infty+∞ and so would be considered equal despite the latter being better at all times.

This problem of infinities is one of the standard motivations for exponential discounting of utility itself, also known as _pure time preference_ (Koopmans 1960). For if we assume a finite bound on the level of utility at each time, then exponential discounting guarantees a finite bound on the infinite sum of the stream of utilities. The general study of how to evaluate or compare such unboundedly long streams of utility is known in economics as _intergenerational equity_ (Diamond 1965, Asheim 2010, Pivato and Fleurbaey 2024).

While the infinities here may seem to stem from an implausible modelling assumption (a literally infinite future), they tend to reappear even on more plausible models. For instance, they reappear unless one is absolutely certain the future is finite. Even if one is certain that the future is finite, if its duration is probabilistic, a declining hazard rate can make the _expected_ duration infinite. And even if we are certain that there is a constant hazard rate (such that the objective expected duration is finite), uncertainty about how low this rate is can easily make the subjective expected duration infinite once more.[13](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-14) So it is not unreasonable for economists to see if one can address the infinite utility stream at the heart of the problem.

By simply taking the hyperreal sum of the sequence of utilities, we can evaluate infinite streams of utility without discounting.[14](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-15) This assigns the value ω\\omegaω to the stream ⟨1,1,1,…⟩\\langle 1, 1, 1, \\ldots \\rangle⟨1,1,1,…⟩ and 2ω2\\omega2ω to ⟨2,2,2,…⟩\\langle 2, 2, 2, \\ldots \\rangle⟨2,2,2,…⟩. More generally, if all utilities in a stream are multiplied by mmm, the value of the stream is multiplied by mmm, and if ccc is added to every utility, the value of the stream is increased by cωc \\omegacω. If ccc is instead added to a single utility, the value of the stream is increased by ccc. For instance, the stream ⟨0,1,1,…⟩\\langle 0, 1, 1, \\ldots \\rangle⟨0,1,1,…⟩ is assigned a value of ω−1\\omega − 1ω−1. The method is thus sensitive to changes to any element of the stream and satisfies the following:

> _**Strong Pareto:**_
>
> For all streams xxx, yyy: if xn≥ynx\_n \\geq y\_nxn​≥yn​ for all n∈Nn \\in \\mathbb{N}n∈N, and xn>ynx\_n \\gt y\_nxn​>yn​ for some n∈Nn \\in \\mathbb{N}n∈N, then xxx is better than yyy.

At the same time it satisfies a form of temporal impartiality:

> _**Finite Anonymity:**_
>
> Reordering finitely many elements of a stream won’t change its value.

It also satisfies other desirable properties from the literature on intergenerational equity: there is no dictatorship of the present or of the future, it can handle exponentially growing utility streams, and it exhibits only infinitesimal impatience (Pivato 2008).

It is instructive to compare the hyperreal method to that of taking the standard sum and taking the standard average. The standard sum works well until the total is infinite, when it is no longer able to distinguish between infinitely valuable streams. It is like a camera whose sensor is washed out with the brightness of the scene, clipping the values to pure white. It is possible to take the average instead (Pivato 2022), allowing us to clearly see the infinite difference in value between ⟨1,1,1,…⟩\\langle 1, 1, 1, \\ldots \\rangle⟨1,1,1,…⟩ and ⟨2,2,2,…⟩\\langle 2, 2, 2, \\ldots \\rangle⟨2,2,2,…⟩. But then we can no longer discern the finite difference between ⟨1,1,1,…⟩\\langle 1, 1, 1, \\ldots \\rangle⟨1,1,1,…⟩ and ⟨0,1,1,…⟩\\langle 0, 1, 1, \\ldots \\rangle⟨0,1,1,…⟩. Taking the average is like putting a dark filter over the camera’s lens — it overcomes the dazzling brightness but limits our ability to make fine distinctions in the shadows.

We say cameras have a limited _dynamic range_ — the ratio between the largest and smallest values they can measure. The dynamic range of the standard sum is unbounded yet finite. It can express arbitrarily large finite ratios, but it is unable to distinguish between infinite ratios. We can adjust where we are using that dynamic range (e.g. by taking an average), but we can’t increase the dynamic range without moving to a more expressive number system.

The hyperreals have a much larger dynamic range. They can handle infinite values while still keeping track of finite differences. They can even handle higher infinities, such as assigning 2ω−12^{\\omega} − 12ω−1 to the stream ⟨1,2,4,…⟩\\langle 1, 2, 4, \\ldots \\rangle⟨1,2,4,…⟩, while still being able to track finite (or even infinitesimal) differences. It is also possible to express these values as averages if we divide them by the count of the sequence (ω\\omegaω). So the average value of ⟨1,1,1,…⟩\\langle 1, 1, 1, \\ldots \\rangle⟨1,1,1,…⟩ is 1 and the average value of ⟨0,1,1,…⟩\\langle 0, 1, 1, \\ldots \\rangle⟨0,1,1,…⟩ is ω−1ω\\frac{\\omega−1}{\\omega}ωω−1​, which equals 1−1ω1 − \\frac1\\omega1−ω1​, and is thus infinitesimally lower. The presence of infinitesimals preserves the infinite dynamic range. Indeed, one can see this hyperreal average as a refinement of the standard average, in the same way that the hyperreal sum and integral are refinements of the standard sums and integrals.

Hyperreal summation also allows us to distinguish streams whose value, while infinite, is a lesser infinity. For example, the stream ⟨1,0,0,1,…⟩\\langle 1, 0, 0, 1, \\ldots \\rangle⟨1,0,0,1,…⟩ (where only times that are square numbers have utility 1) is becoming ever-more sparse as time goes on. It is assigned the value ⌊ω⌋\\lfloor \\sqrt{\\omega} \\rfloor⌊ω​⌋. There is a dense set of such lesser infinites between 0 and ω\\omegaω, and the hyperreal method is able to distinguish all of them.[15](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-16)

The ordering on utility streams induced by hyperreal summation is closely related to the partial order induced by the well-known _overtaking criterion_. This criterion doesn’t assign values to streams, but provides a method for comparing two streams xxx and yyy . It says xxx is better than yyy whenever there is a time after which the partial sum of the initial segment of xxx remains above that of yyy:

x≻y iff ∃T∀t>T:∑i=1txi>∑i=1tyi\\displaystyle x \\succ y \\; \\text{ iff } \\; \\exists T \\;\\; \\forall t \\gt T: \\; \\sum^t\_{i=1} x\_i \\gt \\sum^t\_{i=1} y\_ix≻y iff ∃T∀t>T:i=1∑t​xi​>i=1∑t​yi​

If we are only saying that one stream is (determinately) better than another when its hyperreal sum is higher on all ultrafilters, then the hyperreal approach’s partial ordering of utility streams is exactly that of the overtaking criterion. If we instead evaluate streams according to a particular ultrafilter, F\\boldsymbol{\\mathcal{F}}F, then the complete ordering induced will be a self-consistent refinement of the partial order given by the overtaking criterion.

Thus if all you are interested in is the ordering of utility streams, it is probably simpler to use the overtaking criterion. But if you wish to consider the differences or ratios of their values, or to compare uncertain prospects, or even just to know how valuable a utility stream actually is, you need to go beyond the overtaking criterion.

These two different interpretations of hyperreal summation lead to orderings of utility streams that are either _incomplete_ (failing to rank some pairs) or _ineffable_ and _arbitrary_ (due to the reliance on the axiom of choice to complete the ordering). It would be good to suffer neither of these limitations, but work by Zame (2007), Lauwers (2010), and Dubey (2011) has shown that is not possible. All rankings of utility streams which satisfy Strong Pareto and Finite Anonymity are either incomplete or require the axiom of choice.

Unlike the finite sum, the hyperreal sum can be changed by adding zeros. For example, consider the stream ⟨1,2,3,…⟩\\langle 1, 2, 3, \\ldots \\rangle⟨1,2,3,…⟩. If we insert a neutral initial time period, moving everything else one time period along to make room, we get the stream ⟨0,1,2,3,…⟩\\langle 0, 1, 2, 3, \\ldots \\rangle⟨0,1,2,3,…⟩. But the hyperreal sum of this stream is ω\\omegaω units lower than the first stream. Shifting everything forward in time has made things worse — violating some interpretations of temporal impartiality. However, this is a direct consequence of satisfying any version of Pareto. For ⟨0,1,2,3,…⟩\\langle 0, 1, 2, 3, \\ldots \\rangle⟨0,1,2,3,…⟩ is also the stream we get if we subtract 1 unit of utility from every period, which Pareto requires us to rank as worse.[16](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-17) There isn’t enough structure to the situation to enable us to treat these cases differently, so treating the shifted sequence as worse is a necessary cost to developing a theory that satisfies Pareto.

While finite permutations cannot change the value of a utility stream, infinite permutations can. But again, it is not possible to satisfy Pareto while being invariant to infinite permutations (Lauwers 1998). One can’t have it all, so the hyperreal approach makes a creditable attempt at taking the Pareto path and seeing what kind of a theory can be established there.

## Infinite Populations

In ethics, attempts to evaluate outcomes in terms of their total utility run into divergent sums when considering infinite worlds, containing infinite populations (Cain 1995, Vallentyne & Kagan 1997, Bostrom 2011, Askell 2018). Some philosophers are concerned about this even if our universe is finite (since moral theories should apply in all situations, even hypothetical ones) but it draws additional impetus from the fact that some mainstream cosmological theories suggest a spatially infinite universe containing infinitely many inhabited planets and thus infinitely many morally relevant beings (Bostrom 2011, Tegmark 2014).

This can be conceptualised in terms of an infinite set of persons with their own utilities, or as an infinite 3D space throughout which utility is distributed. I can’t see how to apply the hyperreal method to the former, so will restrict the analysis to cases where the utility is distributed through space and a standard integral over that space diverges to infinity.

Let’s start by looking at how we could solve this if there were a finite amount of utility distributed over the infinite space. We could find the amount of utility by setting up a surface and sweeping it through the space, integrating all the utility that it encounters. We could set up a symmetrical reference volume around the origin (such as a cube or sphere) and grow its size, until its surface sweeps through every point in space. If rrr is the radius of this volume and ρ(r)\\rho(r)ρ(r) is the average density of utility on its surface, then we can integrate these expanding shells as they sweep through all of 3D space:

∫0∞ρ(r)⋅24r2dr∫0∞ρ(r)⋅4πr2dr\\displaystyle \\int^{\\infty}\_0 \\rho(r) \\cdot 24r^2 \\; dr \\qquad \\int^{\\infty}\_0 \\rho(r) \\cdot 4 \\pi r^2 \\; dr∫0∞​ρ(r)⋅24r2dr∫0∞​ρ(r)⋅4πr2dr

To apply the hyperreal theory, we just take the hyperreal values for these integrals.

For example, if the density of utility were constant these would integrate to 8ρω38\\rho \\omega^38ρω3 and 43πρω3\\frac43 \\pi \\rho \\omega^334​πρω3 respectively. Those constants are meaningful: space divides into 8 octants, each with value ρω3\\rho \\omega^3ρω3 for a total of 8ρω38 \\rho \\omega^38ρω3, while 43πω3\\frac43 \\pi \\omega^334​πω3 is the volume of a sphere of radius ω\\omegaω. But we could also normalise these results: dividing by the size of the reference volume of unit radius (888 and 43π\\frac43 \\pi34​π respectively) giving a normalised value of ρω3\\rho \\omega^3ρω3 in both cases.

To avoid complexities of expressing utility as a density, we could also directly construct this integrated utility from its component definite integrals as \[⟨v(R1),v(R2),v(R3)…⟩\]\[ \\langle v(R\_1), v(R\_2), v(R\_3) \\ldots \\rangle \]\[⟨v(R1​),v(R2​),v(R3​)…⟩\], where v(Rn)v(R\_n)v(Rn​) is the utility contained within the region of radius nnn.

As with streams of utility, we get the results that multiplying all utilities by mmm multiplies the total by mmm and adding ccc units of utility to some location increases the total by ccc. This time, adding ccc units to the density of utility at every location adds cω3c \\omega^3cω3 units to the normalised total. We can also calculate the average utility across all spaces. If the utility in any region of unit volume is bounded, then the average will be a finite number which is nudged up or down by infinitesimal amounts when we make finite changes to the distribution of utility. Once again, we have the infinite dynamic range needed to keep track of both infinite and finite changes.

Unfortunately, these results require a privileged origin from which to begin our integration. This is more of an issue for space than it was for time as it is widely held that the physical space of our universe has no privileged origin. Indeed, this is just one of a host of issues for evaluating infinite spaces that arise from the complex nature of our spacetime as described by special and general relativity. So the hyperreal theory can open the door to assessing infinite worlds, but would need substantial further development before it could work in a physically realistic universe.

## Challenges & limitations

While the hyperreal theory of summation and integration shows substantial promise, this current version that I’ve articulated faces a number of challenges and limitations:

1. _Ultrafilter dependence_: The values of some hyperreal sums and integrals depend on the choice of ultrafilter. Possible solutions include treating such cases as undefined, choosing a particular ultrafilter, distinguishing between claims that are true on all/some/no ultrafilters, or finding a way of averaging across ultrafilters.

2. _Grid problem_: The hyperreal value of an improper integral only depends on the values of definite integrals at a countable sequence of points (either the integers or on numbers of the form s±1ns \\pm \\frac1ns±n1​, where sss is a singular point). This ignores any divergent behaviour occurring between these points. Possible responses include characterising the functions where this is an issue and restricting the scope of this method to not apply to them, replacing the hyperreals with a system that doesn’t have this problem, or just accepting this dependence.

3. _Why ω\\omegaω?_: We currently lack a principled reason for choosing the hyperreal ω\\omegaω as the unit by which to measure the infinite. Possible responses include finding such a reason, using a different number system (like the surreals whose ω\\omegaω is more special), or arguing that it doesn’t matter which hyperreal we use and thus choosing ω\\omegaω (or any other number) by fiat to act as the unit of the infinite (much as we could have chosen any platinum rod to act as the metre).[17](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-18)

4. _Why 1ω\\frac1\\omegaω1​?_: Is there a principled reason for using the reciprocal of the infinite bound for determining how close to a singular point a hyperreal bound should come? One alternative would be to deal with vertical asymptotes by integrating against the yyy-axis instead of the xxx-axis, though this gives different values to the integrals, and loses some of the properties we’ve established. Another alternative would be to integrate up to the xxx-coordinate where the yyy-coordinate reaches ω\\omegaω (i.e. to s±f−1(ω)s \\pm f^{−1}(\\omega)s±f−1(ω) instead of s±1ωs \\pm \\frac1\\omegas±ω1​)).

5. _Spillover_: Sums and integrals to infinity are supposed to be summing or integrating over all the positive integers or reals. Summing or integrating up to an infinite hyperreal bound like ω\\omegaω ensures we cover them all. But stopping at ω2\\frac\\omega22ω​ would also achieve this. Including contributions to the total from infinite values of xxx between ω2\\frac\\omega22ω​ and ω\\omegaω seems inappropriate. But since there is no least infinite hyperreal, all hyperreal stopping points would appear to include too large a domain. (An equivalent issue exists for −ω−\\omega−ω and for integrating to within 1ω\\frac1\\omegaω1​ of a singular point.)

6. _Lack of closure_: These hyperreal methods allow us to sum or integrate functions over the reals and provide a result that may not be a real number. But these methods don’t directly let us sum or integrate functions of those non-real outputs. So while extending our number system to the hyperreals may solve the old problems, it also raises new problems it cannot solve. This is a particular issue when taking a sum or integral across two orthogonal dimensions, as we can’t just use the familiar approach of nested sums or integrals.


A different kind of critique concerns whether the use of the hyperreals is really necessary for achieving these results. Can’t we order divergent sums and integrals with other methods, and even have some quantitative measures of how large each is? Most notably, we could compare them via their asymptotic behaviour and get a conception of size via the functional form of the antiderivative, F(x)F(x)F(x). Indeed, one might even ask if the hyperreal approach isn’t just ‘asymptotics in disguise’?

There is much I’m sympathetic to in this critique. I don’t make any claim that the hyperreal approach is _necessary_ for evaluating divergent sums and integrals. Indeed, if any sequence ⟨xn⟩\\langle x\_n \\rangle⟨xn​⟩, representing xxx, overtakes a sequence ⟨yn⟩\\langle y\_n \\rangle⟨yn​⟩, representing yyy, then x>yx \\gt yx>y regardless of which ultrafilter is used. So this ultrafilter-independent core of the ordering could clearly be modelled directly with asymptotics.

However, much the same could be said about real numbers. The Cauchy sequence construction represents each real as an equivalence class of sequences of rational numbers and compares them by the asymptotics of those sequences. So in some sense irrational numbers are also just asymptotics in disguise. But something substantial would be lost if we treated all claims about irrational numbers that way. e.g. if instead of saying that the area of the circle was πr2\\pi r^2πr2, we had to say that despite having no (rational) area, its area asymptotically approaches that of a regular polygon of radius rrr as the number of its sides, nnn, increases to infinity. We could make do with this, but mathematics would be needlessly harder. If we’d never treated π\\piπ as being a proper number, we couldn’t use it in our equations, without wrapping every line where it appears (or could be the result) in a complicated limit.

Treating π\\piπ (and the other irrationals) as bona fide numbers greatly facilitated the progress of mathematics by making mathematics more eloquent; less cumbersome. The same is true for the complex numbers. My contention is that _despite_ hyperreal summation and integration being largely equivalent to asymptotics, it is nevertheless important to assign _numbers_ to these sums and integrals, and that the hyperreals can perform that job.

There are some important limitations of hyperreal summation and integration as applied to decision theory, economics, and ethics.

In all these areas, it always provides the same partial ordering as the appropriate type of overtaking criterion. So it only really goes further than the overtaking criterion if you are committing to a particular ultrafilter (which refines this into a complete ordering at the cost of arbitrariness and ineffability) or if you want to _evaluate_ options, not just to compare them. This could be for the purpose of using their numerical representations in further calculations, or more philosophically, to show that the paradigm of _options having values_ need not be revised in light of infinite cases.

I’ve presented hyperreal summation and integration as an attempt to solve problems of evaluating options comprised of infinitely many parts, each with finite value. There are many other challenges related to infinities that it doesn’t attempt to solve. For example, it doesn’t immediately offer any way of evaluating indivisible parts of infinite value. The hyperreals may be rich enough to ascribe values to such parts, but the theory presented here doesn’t show which values to assign.

Another challenge is _fanaticism_ — the fact that on many theories of assessing infinite options, even an arbitrarily small finite chance of an infinitely valuable option beats all finitely valuable options. The theories I’m outlining here are fanatical in this sense. But since one can find troubling analogues of fanaticism even in large enough finite cases (Bostrom 2009), I’m not sure the solution (if any) lies in how one deals with the infinite.

There are also many challenges and paradoxes of infinite choice — problems that arise when one has infinitely many simultaneous options or an infinite succession of choices. The hyperreal theory is not put forward as an attempt to solve these, so they would need to be resolved in some other way.

In finite cases, one can easily connect the theories of how we sum across possibility, time, and space, into a coherent whole. At its simplest, we can just nest the sums or integrals, treating time, space, and possibility as different dimensions in which utility can reside (Broome 1991). But there are challenges in connecting the hyperreal sums or integrals in this way. For example, doing so would suggest that a world consisting of ω\\omegaω utility at each time (the utility stream ⟨ω,ω,ω,…⟩\\langle \\omega, \\omega, \\omega, \\ldots \\rangle⟨ω,ω,ω,…⟩) would have a value of ω2\\omega^2ω2. But this value has already been assigned to the utility stream ⟨1,3,5,…⟩\\langle 1, 3, 5, \\ldots \\rangle⟨1,3,5,…⟩ — a world that is strictly worse at all times. There is a subtle incompatibility between the idea of ω2\\omega^2ω2 as the product of two infinite dimensions, and as the value of infinite quadratic growth in a single dimension. This would need to be resolved to create a fully satisfactory theory across all dimensions at once. One possible route forward is to adopt the approach we used for multiple spatial dimensions: starting with a finite ‘volume’ that extends in all the dimensions and to integrating it until it covers the entire space.[18](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-19)

There are also two issues that affect the hyperreal theory of infinite expectations.

First, it violates a version of the _sure-thing principle_: that the value assigned to a prospect should never be higher than that of all its possible outcomes. For example, the hyperreal method suggests a ticket for a St Petersburg gamble is worth an infinite value (⌊log⁡2(ω)⌋\\lfloor \\log\_2(\\omega) \\rfloor⌊log2​(ω)⌋) so we should be indifferent to trading away a guaranteed outcome worth that value to get such a ticket. However, we know with certainty that after the gamble has been resolved, we will get a finite reward and regret the trade (Russell & Isaacs 2020). So any infinite valuation would be too high. But there is a corresponding problem if we assign any finite value to the gamble: for all finite valuations, there is a finite version of the St Petersburg gamble (truncated at some large number of coin tosses) that has expected value exceeding our valuation, so the full gamble must be worth more than we said.

One possible way out would be to lean on the novel fact that the hyperreal theory assigns a decidedly low infinite value to the St Petersburg gamble. We could deny that guaranteed outcomes can have lesser infinites as values, allowing us to order these divergent gambles above all finite outcomes but below all infinite ones. However, as lesser infinite values naturally come up in the study of infinite time and space, this would come at a serious cost to the larger project of a uniform approach to all of these infinities. Ultimately, my best guess is that we need to abandon this version of the sure-thing principle in infinite contexts. After all, Cain (1995) already showed that similar dominance arguments must fail in infinite cases. However, abandoning it will no-doubt have a price and is a downside of this approach.

Second, I suggested we calculate these by the hyperreal interpretation of the standard formula for the mean, where we sum (or integrate) over each utility multiplied by its probability. However, this involves summing over the domain of utility instead of summing over the domains of time or space. This is a problem because the hyperreal sum and integral are invariant to vertical translations and dilations but aren’t invariant to horizontal translations and dilations. Thus, the hyperreal theory can’t cleanly derive that a prospect with twice as much utility in each state is twice as good (it ends up depending on the ultrafilter).

One promising path forward is to rearrange the way we do the sum: to integrate over probability from 0 to 1, with states arranged in order of increasing utility. In other words, we take the expected value to be the hyperreal integral of the distribution’s quantile function: summing up the contribution to the EV from each quantile in turn. For the St Petersburg gamble, the integral would be:

![Bar chart showing the St Petersburg gamble probability distribution with utility on y-axis and probability on x-axis, displaying exponentially increasing payoffs with decreasing probabilities that converge to log omega in hyperreal expected value theory](https://images.ctfassets.net/4owxfjx3z3if/1DudWxRKHzVgi6CI8nh1be/ac17ecfd9fad2214fa303671c0465268/st-petersburg-gamble-distribution.png?w=3840&q=75&fm=webp)


This may be able to form the basis of a hyperreal expected value that is linear in the utilities of the states as well as in their probabilities.

## Further Work

This paper has only provided a quick sketch of the mathematical theory of hyperreal summation and integration so there is much more work needed to properly flesh it out. Some natural paths for further development include:

- Characterising the set of functions whose hyperreal integrals take on values that are independent of the ultrafilter used;

- Developing the idea of averaging over ultrafilters;

- Developing versions based on other infinite number systems, such as the surreal numbers.[19](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-20)


There are also many ways in which one could develop the applications of the theory. Each of the areas we’ve covered could be explored much more deeply: teasing out the consequences of evaluating infinite options in this manner and comparing these with both competing theories and the known impossibility results in that area.

In each of these areas, the hyperreal approach could also be applied to other forms of aggregation beyond direct sums and integrals. For example, in ethics _prioritarianism_ (Parfit 1997) gives more priority to helping the worse off by transforming people’s utilities with a concave function before summing them, and _risk-weighted expected utility theory_ (Buchak 2013) allows for risk aversion about utility itself through use of a more complex kind of sum across states. Many such systems can be adapted to use the hyperreal sum. Even the product of utilities can be adjusted to a hyperreal infinite product in the natural way.

And there are more areas where hyperreal summation and integration could be of use:

Quantum field theory is a very different topic that is also beset by infinities. In a variety of situations, crucial sums and integrals diverge to infinity. Physicists have developed a diverse set of techniques under the names of regularization and renormalisation to work around these issues. It is possible that hyperreal summation and integration (or some development of these theories) would help.

For example, in some notable physical situations infinities arise from the sums 1+1+1+…1 + 1 + 1 + \\ldots1+1+1+… and 1+2+3+…1 + 2 + 3 + \\ldots1+2+3+…. Physicists have had substantial success using zeta regularisation to assign these sums the values −12− \\frac12−21​ and −112−\\frac{1}{12}−121​, respectively. Despite the bizarreness of assigning a negative fractional value to a sum of strictly positive integers, many summation techniques assign them precisely these values.

Looking more carefully at what is happening in the physical situations, the sums appear to contain a term that is diverging to infinity (linearly for the first; sum quadratically for the second) and a term that is converging to a finite number ( −12− \\frac12−21​ and −112−\\frac{1}{12}−121​ respectively). Other aspects of the physical situations suggest that the infinite parts cancel with another (intuitively equal) infinity, leaving the residual finite part. While this might seem fanciful, in the case of the Casimir effect the resulting −112−\\frac{1}{12}−121​ has been physically observed, suggesting that this really is the correct answer, even if we don’t yet know how to rigorously justify it.

This looks like a situation where the infinite dynamic range of hyperreal numbers could help. For example, the situations above might be explicable if the integral related to 1+1+1+…1 + 1 + 1 + \\ldots1+1+1+… took the value ω−12\\omega − \\frac12ω−21​ and the integral related to 1+2+3+…1 + 2 + 3 + \\ldots1+2+3+… took the value 12ω2−112\\frac12 \\omega^2 − \\frac{1}{12}21​ω2−121​. These hyperreal valuations do not immediately fall out of the theory presented here, but they do appear to be plausible values of the smoothed functions corresponding to the discrete infinite sums. I believe that further work here is likely to turn up a way of making this precise. This may provide some help with this longstanding technical problem of infinites in physics. (And it may also be of mathematical interest — illuminating the relationship between divergent sums and integrals.)

Hyperreal summation and integration might also be able to provide the foundation for a theory of infinitesimal probabilities. A key aim of such a theory is to avoid assigning probability zero to events that can happen (such as to each possible infinite sequence of coin flips). One way to do this is through the survival function, S(t)S(t)S(t), which represents the chance that an event still hasn’t occurred by time ttt. For example, let the event be that the coin has at some point landed tails. If the coin is being flipped at rate λ\\lambdaλ starting at time t0t\_0t0​ then:

S(t)={1,t<t02−⌊r(t−t0)+1⌋,t≥t0\\displaystyle S(t) = \\begin{cases} 1 & , t \\lt t\_0 \\\ 2^{-\\lfloor r(t-t\_0) + 1 \\rfloor} & , t \\geq t\_0 \\end{cases}S(t)={12−⌊r(t−t0​)+1⌋​,t<t0​,t≥t0​​

We could begin a theory of infinitesimal probability by proposing that the chance an event never happens be represented by the value of the survival function at time ω\\omegaω (after all finite times have passed).[20](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-21) Thus, the probability of flipping all heads (or of any other particular sequence) would be:

S(ω)=2−⌊λ(ω−t0)+1⌋\\displaystyle S(\\omega) = 2^{-\\lfloor \\lambda(\\omega - t\_0) + 1 \\rfloor}S(ω)=2−⌊λ(ω−t0​)+1⌋

This theory would assign non-zero infinitesimal probabilities to all processes that have a non-zero chance of surviving for every finite duration. And these assigned probabilities have a variety of attractive properties such as:

- starting the sequence of flips one flip later doubles the probability of all heads

- doubling the rate of flips squares the probability

- the probability of heads on every odd flip is the square root of the probability, which is the same as if we halved the rate

- the probability of _all heads or all tails_ is twice the probability of all heads

- the probability of rolling all 6s on an infinite sequence of die rolls is given by the same formula, but with a 6 replacing the 2

- as a continuous example, the probability of an unstable isotope never decaying is e−λ(ω−t0)e^{−\\lambda(\\omega − t\_0)}e−λ(ω−t0​), where λ\\lambdaλ is its decay rate

- The probability of surviving an infinite succession of hazards with probabilities 12,13,14,…\\frac12, \\frac13, \\frac14, \\ldots21​,31​,41​,… is 1⌊λ(ω−t0)+1⌋\\frac{1}{\\lfloor \\lambda(\\omega − t\_0) + 1 \\rfloor}⌊λ(ω−t0​)+1⌋1​


This theory does require a zero and unit for time, but canonical answers can be found (the Big Bang & Planck time). And for many questions about the comparative sizes of infinitesimal probabilities the particular zero and unit factor out anyway. There is clearly a lot more to develop, but a theory of infinitesimal probabilities along these lines appears promising.[21](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-22)

## Conclusions

We have explored a novel mathematical technique for summation and integration that assigns intuitive fine-grained infinite values to many divergent sums and integrals. Given the hyperreal numbers, the method is extremely simple and elegant: we just took the natural generalisation of the finite sum and integral to hyperreal bounds and then reinterpreted infinite sums and integrals as these generalised versions with a bound of ω\\omegaω.

The resulting theory is of mathematical interest as a way of refining the concept of the infinite sum and improper integral, and it can be applied to many areas outside of mathematics where the infinities thrown up by the standard theory block our ability to analyse and compare different options. To do so, we just took the natural infinite sums and integrals that would define the relevant values and used the new method to evaluate them. This produced intuitive results that avoided many of the problems of infinity that had plagued these areas.

One upshot of this is that we should be very skeptical about applying familiar rules for the infinities of the extended reals and cardinal numbers when evaluating or comparing infinite options. For example, Hájek (2003) argued that because the expected value of Pascal’s Wager is +∞+\\infty+∞, the value of a 1 in a million chance of receiving the wager is also +∞+\\infty+∞ and so would (surprisingly) be equally good. But while this is true if we measure infinite value with the extended reals, it is false if we measure it with the hyperreals. Since Hájek’s reasoning also violates the foundational state-wise dominance principle of decision theory (Askell 2018), having a theoretical alternative that preserves this principle is welcome.

Similarly, there is a bijection between the people in an infinite universe where 99% of people on each planet are happy while 1% are miserable and the people in a universe with the opposite ratio. But this doesn’t compel us to treat them as equally valuable. The _cardinalities_ of happy and miserable people are the same in both cases, but it is far from clear that cardinal numbers are the appropriate infinite number system with which to evaluate this case, and the hyperreal approach allows us to reach the intuitive answer that the former universe is infinitely superior.

In his groundbreaking work on the long-term prospects for humanity, Freeman Dyson (1979) suggested that an infinite future is possible, even with a finite amount of energy. By alternating fixed-length periods of activity with exponentially increasing periods of hibernation, that finite energy could produce infinitely many years of conscious experience. This is usually taken as producing a future that would be equally valuable as one that didn’t have the hibernation periods. But with this new lens of hyperreal summation, we can see that even were this scheme workable, the increasingly sparse stream of utility it produces may have a value on the order of log⁡(ω)\\log(\\omega)log(ω) — quite an impoverished infinity compared to that of constant flourishing over all time.

So I suggest that we refrain from treating claims of infinite value as claims that this value obeys the mathematics of the extended reals or cardinal numbers. Instead we should take most claims of infinite value merely to be saying that the value exceeds all finite levels. How it then compares with other infinitely valuable options is a further matter, and is not immediately given by facts about the extended reals or cardinal numbers.

Indeed, the possibility of hyperreal summation and integration allows a conception of infinite value that behaves very much like finite value. Doubling a positive value makes it twice as good, doubling the chance of a positive outcome makes it twice as good in expectation, subtracting 7 units of utility from a part of an outcome makes the outcome worse (by 7 units) etc. With the high dynamic range offered by the hyperreals, you can take in the brilliance of the infinite and still see the subtle shading within it, while remaining sensitive to the variation in the darkness of the finite shadows. Much of this is due to the transfer principle, where all first-order properties of the reals are inherited by the hyperreals. Or as Leibniz (1702) put it: ‘the rules of the finite are found to succeed in the infinite.’ We can thus see hyperreal summation and integration as fleshing out a Leibnizian conception of infinite value, offering an alternative to the Cantorian conception.

In this view, the appropriate family of infinite numbers to describe these infinite situations (the hyperreals) is one that is less familiar, but ultimately more intuitive. The peculiar properties of the extended reals (where x+1x + 1x+1 can equal xxx) or of the cardinal numbers (as described in the story of Hilbert’s hotel) come from their coarse-grained treatment of the infinite. Once we have the fine-grained conception offered by the hyperreals, a more intuitive picture of the infinite emerges.

Access to such methods should make us less afraid of the infinite — less inclined to introduce assumptions or values that would otherwise be unwarranted simply in order to make the infinities go away. For it looks like there is a way of answering these questions that avoids the decision paralysis that comes with the coarse-grained infinities. And with such prospects available, it would be rash to change our fundamental values about finite situations out of fear that they can’t be made to work in infinite situations.[22](https://www.forethought.org/research/evaluating-the-infinite#user-content-fn-23) While we don’t yet have a fully developed theory for evaluating infinite options using fine-grained infinite values, we have scarcely begun to explore even the most natural infinite number systems, and what little we’ve seen counsels hope rather than despair.

## References

Asheim, Geir B., 2010. ‘Intergenerational Equity’, _Annual Review of Economics_, 2: 197–222.

Askell, Amanda, 2018. _Pareto principles in infinite ethics_. Ph.D. thesis, New York University.

Bernoulli, Daniel, 1738 \[1954\]. ‘Specimen Theoriae Novae de Mensura Sortis’, _Commentarii Academiae Scientiarum Imperialis Petropolitanae_, 5: 175–192. English translation, 1954, ‘Exposition of a New Theory on the Measurement of Risk’, _Econometrica_, 22(1): 23–36.

Błaszczyk, Piotr, 2021. ‘Galileo’s paradox and numerosities’. _Philosophical Problems in Science (Zagadnienia Filozoficzne W Nauce)_, 70: 73–107.

Bostrom, Nick, 2009. ‘Pascal’s Mugging’. _Analysis_, 69(3): 443–445.

Bostrom, Nick, 2011. ‘Infinite ethics’. _Analysis and Metaphysics_, 10: 9–59.

Broome, John, 1991. _Weighing goods: equality, uncertainty, and time_, Wiley-Blackwell.

Buchak, Lara, 2013, _Risk and Rationality_, New York: Oxford University Press.

Cain, James, 1995. ‘Infinite utility’. _Australasian Journal of Philosophy_, 73(3): 401–404.

Colyvan, Mark, 2008. ‘Relative Expectation Theory’, _The Journal of Philosophy_, 105(1): 37–44.

Diamond, Peter A., 1965. ‘The evaluation of infinite utility streams’. _Econometrica_, 33: 170–77.

Dubey, Ram S., 2011. ‘Fleurbaey-Michel conjecture on equitable weak Paretian social welfare order’. _Journal of Mathematical Economics_, 47 (4-5): 434–439.

Dyson, Freeman J., 1979. ‘Time without end: Physics and biology in an open universe’. _Reviews of Modern Physics_, 51(3): 447–460.

Easwaran, Kenny, 2008. ‘Strong and Weak Expectations’, _Mind_, 117: 633–641.

Goldblatt, Robert, 1998. ‘Lectures on the hyperreals’. In _Graduate texts in mathematics_, 1(1): 83–105.

Hájek, Alan, 2003. ‘Waging War on Pascal’s Wager’. _Philosophical Review_, 112(1): 27–56,

Keisler, H. Jerome, 1976. _Foundations of Infinitesimal Calculus_, (Prindle, Weber & Schmidt).

Koopmans, Tjalling C, 1960. ‘Stationary ordinal utility and impatience’. _Econometrica_, 28(2): 287–309.

Lauwers, Luc, 1998. ‘Intertemporal objective functions: Strong Pareto versus anonymity’. _Mathematical Social Sciences_, 35(1): 37–55.

Lauwers, Luc, 2010. ‘Ordering infinite utility streams comes at the cost of a non-Ramsey set’. _Journal of Mathematical Economics_, 46(1): 32–37.

Lauwers, Luc, & Peter Vallentyne, 2016. ‘Decision Theory Without Finite Standard Expected Value’, _Economics & Philosophy_, 32: 383–407.

Leibniz, Gottfried Wilhelm, and Leroy E. Loemker, 1970. _Philosophical Papers and Letters (2nd ed.)_. Dordrecht: D. Reidel, p. 544.

Tegmark, Max, 2014. _Our Mathematical Universe_, (Allen Lane).

Ord, Toby, 2023. ‘The Lindy Effect’. arXiv:2308.09045 \[physics.soc-ph\].

Parfit, Derek, 1997. ‘Equality and priority’. Ratio, 10(3): 202–221.

Pivato, Marcus, 2008. ‘Sustainable preferences via nondiscounted, hyperreal intergenerational welfare functions’. MPRA Paper No. 7461.

Pivato, Marcus, 2014. ‘Additive representation of separable preferences over infinite products’. _Theory and Decision_, 77(1): 31–83.

Pivato, Marcus, 2022. ‘A characterization of Cesàro average utility’, _Journal of Economic Theory_, 201.

Pivato, Marcus and Marc Fleurbaey, 2024. ‘Intergenerational equity and infinite-population ethics: a survey’. _Journal of Mathematical Economics_, 113.

Robinson, Abraham, 1966. _Non-standard analysis_. North-Holland Publishing Co., Amsterdam.

Russell, Jeffrey Sanford, & Yoaav Isaacs, 2020. ‘Infinite Prospects’, _Philosophy & Phenomenological Research_, 103: 178–198.

Vallentyne, Peter, & Shelly Kagan, 1997. ‘Infinite Value and Finitely Additive Value Theory’, _The Journal of Philosophy_, 94(1): 5–26.

Weitzman, Martin, 1998. ‘Why the Far-Distant Future Should Be Discounted at its Lowest Possible Rate.’ _Journal of Environmental Economics and Management_, 36(3): 201–8.

Zame, William R.,  2007. ‘Can intergenerational equity be operationalized?’, Theoretical Economics, 2:187–202.